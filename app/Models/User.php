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

    protected $camp_permissions = [];

    protected $rolePermissions = null;

    protected $camp_roles = [];

    public $resourceNameInMandarin = '義工資料';

    public $resourceDescriptionInMandarin = '義工帳號的資料，非必要請勿使用這個權限。';

    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->initializeTraits();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->camp_permissions = collect($this->camp_permissions);
        $this->camp_roles = collect($this->camp_roles);
    }

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

    public function permissionsRolesParser($camp) {
        /**
         *  1. 取得該義工於營隊內的所有職務
         *  2. 取出所有權限的聯集，並以條列方式呈現
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
        $this->camp_permissions = $parsed;
        $this->camp_roles = $this->roles()->where('camp_id', $camp->id)->get();
        return $parsed;
    }

    public function canAccessResource($resource, $action, $camp, $context = null, $target = null) {
        if (!$this->camp_roles) {
            $this->camp_roles = $this->permissionsRolesParser($camp);
        }
        if (!$resource) {
            return false;
        }
        $class = get_class($resource);
        if ($context == "volunteerList") {
            if(str_contains($class, "Applicant") || str_contains($class, "User")) {
                $class = "App\Models\Volunteer";
            }
        }

        // 全域權限
        $permissions = $this->permissions;
        // 營隊權限
        if (!$this->rolePermissions) {
            $this->rolePermissions = self::with('roles.permissions')->whereHas('roles', function ($query) use ($camp) {
                return $query->where('camp_id', $camp->id);
            })->where('id', $this->id)->get()->pluck('roles')->flatten()->pluck('permissions')->flatten()->unique('id')->values();
        }
        $permissions = $permissions ? collect($permissions)->merge($this->rolePermissions) : $this->rolePermissions;
        $forInspect = $permissions->where("resource", "\\" . $class)->where("action", $action)->first();
        if ($forInspect) {
            switch ($forInspect->range_parsed) {
                // 0: na, all
                case 0:
                    return true;
                // 1: volunteer_large_group
                case 1:
                    if ($class == "App\Models\Volunteer" && $resource->roles) {
                        return $resource->roles->whereIn("section", $this->roles()->where('camp_id', $camp->id)->pluck("section"))->count();
                    }
                    if ($class == "App\Models\Applicant" && $resource->user?->roles) {
                        return $resource->user->roles->whereIn("section", $this->roles()->where('camp_id', $camp->id)->pluck("section"))->count();
                    }
                    if (($class == "App\Models\User" || $class == "App\User") && $resource->roles) {
                        return $resource->roles->whereIn("section", $this->roles()->where('camp_id', $camp->id)->pluck("section"))->count();
                    }
                    return false;
                // 2: learner_group
                // ★：學員小組的意思除了是「同一個小組的學員」以外，還包含「護持同一個學員小組的義工」
                case 2:
                    $roles = $this->roles()->where('group_id', '<>', null)->where("camp_id", $camp->id);
                    if (str_contains($class, "Applicant") && $context == "onlyCheckAvailability") {
                        return $roles->first();
                    }

                    if (str_contains($class, "Applicant") && !str_contains($class, "Group")) {
                        return $roles->firstWhere('group_id', $target->group_id);
                    } elseif (str_contains($class, "Volunteer")) {
                        return $roles->firstWhere(
                            'group_id',
                            $target->user->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id
                        )
                        ||
                        ($target->user->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id &&
                        $this->roles()->where("camp_id", $camp->id)->where(function ($query) {
                            $query->where("position", "like", "%關懷小組%")
                                ->orWhere("position", "like", "%關懷服務組%")
                                ->orWhere("position", "like", "%關服組%");
                        })->firstWhere('all_group', 1));
                    }

                    if ($class == "App\Models\ContactLog") {
                        return $roles->firstWhere('group_id', $target->group_id);
                    }
                    return false;
                // 3: person
                case 3:
                    if (str_contains($class, "Applicant") && $context == "onlyCheckAvailability") {
                        return $this->caresLearners->whereIn('batch_id', $camp->batchs->pluck('id'))->first();
                    }
                    if ($class == "App\Models\ApplicantGroup") {
                        return $this->caresLearners->where('group_id', '<>', null)->where("group_id", $resource->id)->first();
                    }
                    // 沒這回事
                    if ($class == "App\Models\CampOrg") {
                        return false;
                    }
                    if ($class == "App\Models\Applicant") {
                        return $this->caresLearners->where('group_id', '<>', null)->where("id", $resource->id)->first();
                    }
                    if ($class == "App\Models\ContactLog") {
                        return $this->caresLearners->where('group_id', '<>', null)->where("id", $target->id)->first();
                    }
                    return false;
                default:
                    return false;
            }
        }
        else {
            return false;
        }
    }
}
