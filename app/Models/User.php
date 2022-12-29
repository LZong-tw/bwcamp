<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Config;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\EmailConfiguration;

class User extends Authenticatable
{
    use Notifiable, EmailConfiguration, LaratrustUserTrait;

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
    public function legace_roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');
    }

    public function groupOrgRelation()
    {
        return $this->belongsToMany(CampOrg::class, 'org_user', 'user_id', 'org_id');
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance) {
        $this->setEmail($this->role_relations->first()->role->camp->table ?? "");
        app(\Illuminate\Contracts\Notifications\Dispatcher::class)->send($this, $instance);
    }
}
