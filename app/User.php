<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\EmailConfiguration;

class User extends Authenticatable
{
    use Notifiable;
    use EmailConfiguration;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $resourceNameInMandarin = '義工';

    protected $hasRole = null;

    public function getPermission($top = false, $camp_id = null, $function_id = null)
    {
        if(!$top) {
            if (!$this->hasRole) {
                $this->hasRole = \App\Models\RoleUser::join('roles', 'roles.id', '=', 'role_user.role_id')->where('user_id', $this->id)->orderBy('level', 'asc')->get();
            }
            $hasRole = $this->hasRole;
            if($hasRole->count() == 0) {
                $empty = new \App\Models\Role();
                $empty->level = 999;
                return $empty;
            }
            return $hasRole->first();
        } elseif($top) {
            if($camp_id) {
                return \DB::table('roles')->where('camp_id', $camp_id)->whereIn('id', $this->role_relations->pluck('role_id'))->orderBy('level', 'desc')->first();
            } else {
                return \DB::table('roles')->whereIn('id', $this->role_relations->pluck('role_id'))->orderBy('level', 'desc')->get();
            }
        }
    }

    public function role_relations()
    {
        return $this->hasMany('App\Models\RoleUser');
    }

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->hasMany('App\Models\RoleUser');
    }

    public function legace_roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance)
    {
        $this->setEmail($this->role_relations->first()->role->camp->table ?? "");
        app(\Illuminate\Contracts\Notifications\Dispatcher::class)->send($this, $instance);
    }
}
