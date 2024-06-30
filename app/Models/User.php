<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\EmailConfiguration;

class User extends Authenticatable
{
    use Notifiable;
    use EmailConfiguration;
    use LaratrustUserTrait;

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

    public function getPermission($top = false, $camp_id = null, $function_id = null)
    {
        if(!$top) {
            $hasRole = \App\Models\RoleUser::join('roles', 'roles.id', '=', 'role_user.role_id')->where('user_id', $this->id)->orderBy('level', 'asc')->get();
            if($hasRole->count() == 0) {
                $empty = new \App\Models\Role();
                $empty->level = 999;
                return $empty;
            }
            return $hasRole->first();
        } elseif($top) {
            if($camp_id) {
                return \DB::table('roles')->where('camp_id', $camp_id)->whereIn('id', $this->legace_roles()->pluck('role_id'))->orderBy('level', 'desc')->first();
            } else {
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
    public function notify($instance)
    {
        $this->setEmail($this->role_relations->first()->role->camp->table ?? "");
        app(\Illuminate\Contracts\Notifications\Dispatcher::class)->send($this, $instance);
    }

    public function caresLearners()
    {
        return $this->belongsToMany(Applicant::class, CarerApplicantXref::class, 'user_id', 'applicant_id', 'id', 'id');
    }

    public function application_log()
    {
        return $this->belongsToMany(Applicant::class, UserApplicantXref::class, 'user_id', 'applicant_id', 'id', 'id');
    }

    public function canAccessResult()
    {
        return $this->hasMany(Ucaronr::class);
    }

    // protected function canAccessResourceLocal(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => ucfirst($value),
    //     );
    // }

    public function applicants($camp_id)
    {
        $vbatch_id = Camp::find($camp_id)->vcamp->batchs->pluck('id');
        $applicants_all = $this->application_log;
        $applicants_filtered = $applicants_all->whereIn('batch_id', $vbatch_id);
        return $applicants_filtered;
    }
    public function permissionsRolesParser($camp)
    {
        /**
         *  1. 取得該義工於營隊內的所有職務
         *  2. 取出所有權限的聯集，並以條列方式呈現
         */
        $permissions = $this->roles()->where('camp_id', $camp->id)->get()
                        ->filter(static fn ($role) => $role->permissions->count() > 0)
                        ->map(static fn ($role) => $role->permissions)
                        ->flatten()->unique('id')->values();
        $permissions = $permissions->sortBy(["resource", "action"]);
        $parsed = collect();
        $permissions->each(function ($permission) use (&$parsed) {
            $existing = $parsed->where("resource", $permission->resource)->firstWhere("action", $permission->action);
            if ($existing) {
                if ($existing["range_parsed"] < $permission->range_parsed) {
                    $existing["description"] = $permission->description;
                    $existing["range"] = $permission->range;
                    $existing["range_parsed"] = $permission->range_parsed;
                }
            } else {
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

    public function canAccessResource($resource, $action, $camp, $context = null, $target = null, $probing = null)
    {
        if (!$resource) {
            return false;
        }

        $class = get_class($resource);
        if ($resource instanceof \App\Models\Volunteer && $context == "vcampExport") {
            $class = "App\\Models\\Applicant";
        }

        $batch_id = null;
        $region_id = null;
        if ($resource instanceof \App\Models\Applicant || $resource instanceof \App\Models\Volunteer) {
            $batch_id = $resource->batch_id;
            $region_id = $resource->region_id;
        } elseif ($resource instanceof \App\Models\User) {
            $theCamp = $camp->vcamp;
            $theApplicant = $resource->application_log->whereIn('batch_id', $theCamp->batchs()->pluck('id'))->first();
            $batch_id = $theApplicant->batch_id ?? null;
            $region_id = $theApplicant->region_id ?? null;
        }

        $existingAccessResult = $this->canAccessResult()
            ->where('camp_id', $camp->id)
            ->where('batch_id', $batch_id)
            ->where('region_id', $region_id)
            ->where('accessible_id', $target->id ?? null)
            ->where('accessible_type', $class)
            ->first();

        if ($existingAccessResult) {
            return $existingAccessResult->can_access;
        } else {
            return $this->fillingAccessibleReult($resource, $action, $camp, $context, $target, $probing);
        }
    }

    public function fillingAccessibleReult($resource, $action, $camp, $context = null, $target = null, $probing = null)
    {
        $result = $this->getAccessibleResult($resource, $action, $camp, $context, $target, $probing);
        $class = get_class($resource);
        $batch_id = null;
        $region_id = null;
        if ($resource instanceof \App\Models\Applicant || $resource instanceof \App\Models\Volunteer) {
            $batch_id = $resource->batch_id;
            $region_id = $resource->region_id;
        } elseif ($resource instanceof \App\Models\User) {
            $theCamp = $camp->vcamp;
            $theApplicant = $resource->application_log->whereIn('batch_id', $theCamp->batchs()->pluck('id'))->first();
            $batch_id = $theApplicant->batch_id;
            $region_id = $theApplicant->region_id;
        }
        $this->canAccessResult()->firstOrCreate([
            'user_id' => $this->id,
            'camp_id' => $camp->id,
            'batch_id' => $batch_id,
            'region_id' => $region_id,
            'accessible_id' => $target->id ?? null,
            'accessible_type' => $class,
            'can_access' => $result ? 1 : 0
        ]);
        return $result ? true : false;
    }

    public function getAccessibleResult($resource, $action, $camp, $context = null, $target = null, $probing = null)
    {
        if (!$this->camp_roles) {
            $this->camp_roles = $this->permissionsRolesParser($camp);
        }
        if (!$resource) {
            return false;
        }
        if ($context == "vcampExport" && $target) {
            $camp = Vcamp::query()->find($target->camp->id)->mainCamp;
        }
        $class = get_class($resource);

        if ($resource instanceof \App\Models\Volunteer && $context == "vcampExport") {
            $class = "App\\Models\\Applicant";
        }

        // 營隊權限
        // $this->rolePermissions = $this->roles()->where('camp_id', $camp->id)->get()
        //                 ->filter(static fn($role) => $role->permissions->count() > 0)
        //                 ->map(static fn($role) => $role->permissions)
        //                 ->flatten()->unique('id')->values();
        $constraint = function ($query) use ($camp, $resource) {
            $query->where(function ($query) use ($resource, $camp) {
                // 順便做梯次檢查
                if ($resource instanceof \App\Models\Applicant || $resource instanceof \App\Models\Volunteer) {
                    if ($resource->batch_id) {
                        $query->where(function ($query) use ($resource) {
                            $query->where(function ($query) {
                                $query->whereNull('batch_id');
                            })->orWhere(function ($query) use ($resource) {
                                $query->where('batch_id', $resource->batch_id);
                            });
                        });
                    }
                } elseif ($resource instanceof \App\Models\User) {
                    $theCamp = $camp->vcamp;
                    $theApplicant = $resource->application_log->whereIn('batch_id', $theCamp->batchs()->pluck('id'))->first();
                    if ($theApplicant) {
                        $query->where(function ($query) use ($theApplicant) {
                            $query->where(function ($query) {
                                $query->whereNull('batch_id');
                            })->orWhere(function ($query) use ($theApplicant) {
                                $query->where('batch_id', $theApplicant->batch_id);
                            });
                        });
                    }
                }
                // 區域檢查
                if ($resource instanceof \App\Models\Applicant || $resource instanceof \App\Models\Volunteer) {
                    if ($resource->region_id) {
                        $query->where(function ($query) use ($resource) {
                            $query->where(function ($query) {
                                $query->whereNull('region_id');
                            })->orWhere(function ($query) use ($resource) {
                                $query->where('region_id', $resource->region_id);
                            });
                        });
                    }
                } elseif ($resource instanceof \App\Models\User) {
                    $theCamp = $camp->vcamp;
                    $theApplicant = $resource->application_log->whereIn('batch_id', $theCamp->batchs()->pluck('id'))->first();
                    if ($theApplicant) {
                        $query->where(function ($query) use ($theApplicant) {
                            $query->where(function ($query) {
                                $query->whereNull('region_id');
                            })->orWhere(function ($query) use ($theApplicant) {
                                $query->where('region_id', $theApplicant->region_id);
                            });
                        });
                    }
                }
                return $query->where('camp_id', $camp->id);
            });
        };
        $this->rolePermissions = self::with(['roles' => $constraint, 'roles.permissions'])->whereHas('roles', $constraint)->where('id', $this->id)->get()->pluck('roles')->flatten()->pluck('permissions')->flatten()->unique('id')->values();
        $permissions = $this->rolePermissions;
        $forInspect = $permissions->where("resource", "\\" . $class)->where("action", $action)->first();
        if ($forInspect) {
            switch ($forInspect->range_parsed) {
                // 0: na, all
                case 0:
                    return true;
                    // 1: volunteer_large_group
                case 1:
                    if ($class == "App\Models\Volunteer" && $resource->user?->roles) {
                        return $resource->user->roles->whereIn("section", $this->roles()->where('camp_id', $camp->id)->pluck("section"))->count();
                    }
                    if ($class == "App\Models\Applicant" && $resource->user?->roles) {
                        return $resource->user->roles->whereIn("section", $this->roles()->where('camp_id', $camp->id)->pluck("section"))->count();
                    }
                    if (($class == "App\Models\User" || $class == "App\User") && $resource->roles) {
                        return $resource->roles->whereIn("section", $this->roles()->where('camp_id', $camp->id)->pluck("section"))->count();
                    }
                    if ($probing) {
                        dd("first if, case 1", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
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
                    } elseif (str_contains($class, "Volunteer") && $target) {
                        return $roles->firstWhere(
                            'group_id',
                            $target->user?->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id
                        )
                        ||
                        ($target->user?->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id &&
                        $this->roles()->where("camp_id", $camp->id)->where(function ($query) {
                            $query->where("position", "like", "%關懷小組%")
                                ->orWhere("position", "like", "%關懷服務組%")
                                ->orWhere("position", "like", "%關服組%");
                        })->firstWhere('all_group', 1));
                    } elseif (str_contains($class, "User")) {
                        return $roles->firstWhere(
                            'group_id',
                            $target->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id
                        )
                            ||
                            ($target->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id &&
                                $this->roles()->where("camp_id", $camp->id)->where(function ($query) {
                                    $query->where("position", "like", "%關懷小組%")
                                        ->orWhere("position", "like", "%關懷服務組%")
                                        ->orWhere("position", "like", "%關服組%");
                                })->firstWhere('all_group', 1));
                    }

                    if ($class == "App\Models\ContactLog") {
                        return $roles->firstWhere('group_id', $target->group_id);
                    }
                    if ($probing) {
                        dd("first if, case 2", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
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
                    if ($probing) {
                        dd("first if, case 3", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
                    }
                    return false;
                default:
                    if ($probing) {
                        dd("first if, case default", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
                    }
                    return false;
            }
        } elseif ($target && ((str_contains($class, "Applicant") || str_contains($class, "Volunteer")) && $action == "read")) {
            $roles = $this->roles()->where('group_id', '<>', null)->where("camp_id", $camp->id);
            if ($probing) {
                dd("second if", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
            }
            return $roles->firstWhere(
                'group_id',
                $target->user?->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id
            )
            ||
            ($target->user?->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id &&
            $this->roles()->where("camp_id", $camp->id)->where(function ($query) {
                $query->where("position", "like", "%關懷小組%")
                    ->orWhere("position", "like", "%關懷服務組%")
                    ->orWhere("position", "like", "%關服組%");
            })->firstWhere('all_group', 1));
        } elseif ($target && (str_contains($class, "User") && ($context == "vcamp" || $context == "vcampExport") && $action == "read")) {
            $roles = $this->roles()->where('group_id', '<>', null)->where("camp_id", $camp->id);
            if ($probing) {
                dd("third if", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
            }
            return $roles->firstWhere(
                'group_id',
                $target->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id
            )
                ||
                ($target->roles()->where("position", "like", "%關懷小組%")->firstWhere('camp_id', $camp->id)?->group_id &&
                    $this->roles()->where("camp_id", $camp->id)->where(function ($query) {
                        $query->where("position", "like", "%關懷小組%")
                            ->orWhere("position", "like", "%關懷服務組%")
                            ->orWhere("position", "like", "%關服組%");
                    })->firstWhere('all_group', 1));
        } else {
            if ($probing) {
                dd("else, all faild.", $forInspect, $resource, $action, $camp, $context, $target, $permissions);
            }
            return false;
        }
    }

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }
}
