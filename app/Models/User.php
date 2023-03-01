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

    public $resourceNameInMandarin = '義工資料';
    public function legace_roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');
    }

    public function getPermission($top = false, $camp_id = null, $function_id = null) {
        if(!$top){
            $hasRole = \App\Models\RoleUser::join('roles', 'roles.id', '=', 'role_user.role_id')->where('user_id', $this->id)->orderBy('level', 'asc')->get();
            if($hasRole->count() == 0){
                $empty = new \App\Models\Role;
                $empty->level = 999;
                return $empty;
            }
            return $hasRole->first();
        }
        else if($top){
            if($camp_id){
                return \DB::table('roles')->where('camp_id', $camp_id)->whereIn('id', $this->legace_roles()->pluck('role_id'))->orderBy('level', 'desc')->first();
            }
            else{
                return \DB::table('roles')->whereIn('id', $this->legace_roles()->pluck('role_id'))->orderBy('level', 'desc')->get();
            }
        }
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

    public function caresLearners() {
        return $this->belongsToMany(Applicant::class, CarerApplicantXref::class, 'user_id', 'applicant_id', 'id', 'id');
    }

    public function application_log() {
        return $this->belongsToMany(Applicant::class, UserApplicantXref::class, 'user_id', 'applicant_id', 'id', 'id');
    }

    public function permissionParser($camp) {
        /**
         *  1. 取得該義工於營隊內的所有職務
         *  2. 取出所有權限的聯集
         *  3. 以條列方式呈現
         */
        $permissions = $this->roles()->where('camp_id', $camp->id)->get()
                        ->filter(static fn($role) => $role->permissions->count() > 0)
                        ->map(static fn($role) => $role->permissions)
                        ->flatten()->unique('id')->values();
        $permissions = $permissions->sortBy(["resource", "action"]);
        $parsed = collect();
        $permissions->each(function($permission) use (&$parsed) {
            $existing = $parsed->where("resource", $permission->resource)->firstWhere("action", $permission->action);
            if ($existing) {
                if ($existing["range_parsed"] < $permission->range_parsed) {
                    $existing["description"] = $permission->description;
                    $existing["range"] = $permission->range;
                    $existing["range_parsed"] = $permission->range_parsed;
                }
            }
            else {
                $parsed->push([
                    "resource" => $permission->resource,
                    "action" => $permission->action,
                    "description" => $permission->description,
                    "range" => $permission->range,
                    "range_parsed" => $permission->range_parsed,
                ]);
            }
        });

        return $parsed;
    }
}
