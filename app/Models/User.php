<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\EmailConfiguration;
use Illuminate\Support\Facades\DB; // Added
use Illuminate\Database\Eloquent\Relations\MorphMany; // Added (useful for dynamic_stats, ensure it's correctly typed)
use App\Models\Applicant; // Added
use App\Models\Vcamp; // Added

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
    protected $loadedCampContexts = []; // Added for in-memory context caching

    public $resourceNameInMandarin = '義工資料';

    public $resourceDescriptionInMandarin = '義工帳號的資料，非必要請勿使用這個權限。';

    private static $permissions;

    private static $forInspect;

    private static $batchesForPermissionInspection;

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

    public function canAccessResult() {
        return $this->hasMany(Ucaronr::class);
    }

    // protected function canAccessResourceLocal(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => ucfirst($value),
    //     );
    // }

    public function applicants($camp_id) {
        $vbatch_id = Camp::find($camp_id)->vcamp->batchs->pluck('id');
        $applicants_all = $this->application_log;
        $applicants_filtered = $applicants_all->whereIn('batch_id',$vbatch_id);
        return $applicants_filtered;
    }
    public function permissionsRolesParser($camp) {
        /**
         *  1. 取得該義工於營隊內的所有職務
         *  2. 取出所有權限的聯集，並以條列方式呈現
         *  This method might still be used elsewhere, or its logic is largely incorporated into getOrLoadCampContext.
         *  For the purpose of canAccessResource, getOrLoadCampContext will be the primary source.
         */
        $currentCampRoles = $this->roles()->where('camp_id', $camp->id)->with('permissions')->get();
        $permissions = $currentCampRoles
                        ->filter(static fn($role) => $role->permissions->count() > 0)
                        ->map(static fn($role) => $role->permissions)
                        ->flatten()->unique('id')->values();
        $permissions = $permissions->sortBy(["resource", "action"]);
        $parsed = collect();
        $permissions->each(function($permission) use (&$parsed) {
            $existing = $parsed->where("resource", $permission->resource)->firstWhere("action", $permission->action);
            if ($existing) {
                if ($existing["range_parsed"] < $permission->range_parsed) {
                    // Update existing if new permission has broader range by re-mapping
                    $parsed = $parsed->map(function($item) use ($permission) {
                        if ($item['resource'] === $permission->resource && $item['action'] === $permission->action) {
                            return [
                                "resource" => $permission->resource,
                                "action" => $permission->action,
                                "description" => $permission->description,
                                "range" => $permission->range,
                                "range_parsed" => $permission->range_parsed,
                            ];
                        }
                        return $item;
                    });
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
        $this->camp_roles = $currentCampRoles; // Store the fetched roles
        return $parsed;
    }

    protected function getOrLoadCampContext(Camp $camp)
    {
        $campId = $camp->id;
        if (isset($this->loadedCampContexts[$campId])) {
            return $this->loadedCampContexts[$campId];
        }

        $campRoles = $this->roles()->where('camp_id', $campId)->with('permissions')->get();

        $parsedPermissions = collect();
        $allPermissionsForCamp = $campRoles ? $campRoles->pluck('permissions')->flatten()->unique('id')->values()->sortBy(["resource", "action"]) : collect();

        $allPermissionsForCamp->each(function($permission) use (&$parsedPermissions) {
            $existing = $parsedPermissions->where("resource", $permission->resource)->firstWhere("action", $permission->action);
            if ($existing) {
                if ($existing["range_parsed"] < $permission->range_parsed) {
                    $parsedPermissions = $parsedPermissions->map(function($item) use ($permission) {
                        if ($item['resource'] === $permission->resource && $item['action'] === $permission->action) {
                            return [
                                "resource" => $permission->resource,
                                "action" => $permission->action,
                                "description" => $permission->description,
                                "range" => $permission->range,
                                "range_parsed" => $permission->range_parsed,
                            ];
                        }
                        return $item;
                    });
                }
            } else {
                $parsedPermissions->push([
                    "resource" => $permission->resource,
                    "action" => $permission->action,
                    "description" => $permission->description,
                    "range" => $permission->range,
                    "range_parsed" => $permission->range_parsed,
                ]);
            }
        });

        $userGroupIds = $campRoles ? $campRoles->whereNotNull('group_id')->pluck('group_id')->unique()->all() : [];
        $userRegionIds = $campRoles ? $campRoles->whereNotNull('region_id')->pluck('region_id')->unique()->all() : [];
        $userBatchIds = $campRoles ? $campRoles->whereNotNull('batch_id')->pluck('batch_id')->unique()->all() : [];

        $caredLearnerIdsInCamp = [];
        $campBatchesRelation = $camp->batchs();
        if ($campBatchesRelation->exists()) {
             $caredLearnerIdsInCamp = $this->caresLearners()
                                       ->whereIn('applicants.batch_id', $campBatchesRelation->pluck('id')) // Ensure applicants table is joined or accessible
                                       ->pluck('applicants.id')->all();
        }

        $context = [
            'camp_roles' => $campRoles ?: collect(),
            'parsed_permissions' => $parsedPermissions,
            'user_group_ids' => $userGroupIds,
            'user_region_ids' => $userRegionIds,
            'user_batch_ids' => $userBatchIds,
            'cared_learner_ids_in_camp' => $caredLearnerIdsInCamp,
        ];

        $this->loadedCampContexts[$campId] = $context;

        // Optionally update instance properties if other methods rely on them directly,
        // but it's cleaner for getAccessibleResult to use the returned context.
        // $this->camp_permissions = $parsedPermissions;
        // $this->camp_roles = $campRoles ?: collect();

        return $context;
    }

    public function canAccessResource($resource, $action, $camp, $context = null, $target = null, $probing = null) {
        if (!$resource || !$camp || !$camp->id) {
            return false;
        }

        $class = is_string($resource) ? $resource : get_class($resource);
        if ($resource instanceof \App\Models\Volunteer && $context == "vcampExport") {
            $class = "App\\Models\\Applicant";
        }

        $batch_id_for_cache = null;
        $region_id_for_cache = null;
        // Ensure $target->id is accessed only if $target is an object.
        // Ensure $resource->id is accessed only if $resource is an object and has id.
        $accessible_id_for_cache = (is_object($target) && property_exists($target, 'id')) ? $target->id : null;
        if ($accessible_id_for_cache === null && is_object($resource) && property_exists($resource, 'id') && $target === null) {
            $accessible_id_for_cache = $resource->id;
        }

        $camp_id_for_cache = $camp->id;

        if ($resource instanceof \App\Models\Applicant || $resource instanceof \App\Models\Volunteer) {
            $batch_id_for_cache = $resource->batch_id ?? null;
            $region_id_for_cache = $resource->region_id ?? null;
        } elseif ($resource instanceof \App\Models\User) {
            $theCampVcamp = $camp->vcamp;
            if ($theCampVcamp) {
                // Ensure self::$batchesForPermissionInspection is initialized if null
                // Consider if this static property is appropriate or should be instance-based or passed.
                $batchesForPermissionInspection = self::$batchesForPermissionInspection ?? $theCampVcamp->batchs()->get();
                if (self::$batchesForPermissionInspection === null) { // Check again as it might be set by another call
                     self::$batchesForPermissionInspection = $batchesForPermissionInspection;
                }

                // Check if application_log relation exists and is loaded or is a valid method
                if (method_exists($resource, 'application_log')) {
                    $applicationLogRelation = $resource->application_log(); // Get relation instance
                    if ($applicationLogRelation instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                         $theApplicant = $applicationLogRelation->whereIn('batch_id', $batchesForPermissionInspection->pluck('id'))->first();
                         if ($theApplicant) {
                             $batch_id_for_cache = $theApplicant->batch_id;
                             $region_id_for_cache = $theApplicant->region_id;
                         }
                    } elseif ($resource->relationLoaded('application_log') && $resource->application_log instanceof \Illuminate\Support\Collection) {
                        // If already loaded as a collection
                        $theApplicant = $resource->application_log->whereIn('batch_id', $batchesForPermissionInspection->pluck('id'))->first();
                        if ($theApplicant) {
                            $batch_id_for_cache = $theApplicant->batch_id;
                            $region_id_for_cache = $theApplicant->region_id;
                        }
                    }
                }
            }
        }

        $cacheQuery = $this->canAccessResult()
            ->where('camp_id', $camp_id_for_cache)
            ->where('accessible_type', $class)
            ->where('context', $context); // Assuming context is a string or null

        // Apply conditional where for nullable fields
        $cacheQuery->where(function($q) use ($batch_id_for_cache){ $batch_id_for_cache !== null ? $q->where('batch_id', $batch_id_for_cache) : $q->whereNull('batch_id'); });
        $cacheQuery->where(function($q) use ($region_id_for_cache){ $region_id_for_cache !== null ? $q->where('region_id', $region_id_for_cache) : $q->whereNull('region_id'); });
        $cacheQuery->where(function($q) use ($accessible_id_for_cache){ $accessible_id_for_cache !== null ? $q->where('accessible_id', $accessible_id_for_cache) : $q->whereNull('accessible_id'); });

        $existingAccessResult = $cacheQuery->first();

        if ($existingAccessResult) {
            return (bool)$existingAccessResult->can_access;
        }

        // Cache miss, calculate and store
        $result = $this->getAccessibleResult($resource, $action, $camp, $context, $target, $probing);

        $createValues = ['can_access' => $result ? 1 : 0];
        $queryAttributes = [
            'user_id' => $this->id, // Ensure user_id is part of the primary key or unique constraint for Ucaronr
            'camp_id' => $camp_id_for_cache,
            'accessible_type' => $class,
            'context' => $context, // Ensure this is part of the unique key
            'batch_id' => $batch_id_for_cache,
            'region_id' => $region_id_for_cache,
            'accessible_id' => $accessible_id_for_cache,
        ];
        // Ensure all fields in $queryAttributes are fillable or part of Ucaronr's table structure
        $this->canAccessResult()->updateOrCreate($queryAttributes, $createValues);

        return $result;
    }

    // Commenting out the old fillingAccessibleResult method
    /*
    public function fillingAccessibleResult($resource, $action, $camp, $context = null, $target = null, $probing = null) {
        $result = $this->getAccessibleResult($resource, $action, $camp, $context, $target, $probing);
        $class = is_string($resource) ? $resource : get_class($resource);
        $batch_id = null;
        $region_id = null;
        if ($resource instanceof \App\Models\Applicant || $resource instanceof \App\Models\Volunteer) {
            $batch_id = $resource->batch_id;
            $region_id = $resource->region_id;
        }
        elseif ($resource instanceof \App\Models\User) {
            $theCamp = $camp->vcamp;
            // Ensure $theCamp is not null before accessing batchs()
            $theApplicant = null;
            if ($theCamp) {
                 $batchesForInspection = self::$batchesForPermissionInspection ?? $theCamp->batchs()->get();
                 if (self::$batchesForPermissionInspection === null) {
                     self::$batchesForPermissionInspection = $batchesForInspection;
                 }
                 if (method_exists($resource, 'application_log')) {
                    $applicationLogRelation = $resource->application_log();
                     if ($applicationLogRelation instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                        $theApplicant = $applicationLogRelation->whereIn('batch_id', $batchesForInspection->pluck('id'))->first();
                     } elseif ($resource->relationLoaded('application_log') && $resource->application_log instanceof \Illuminate\Support\Collection) {
                        $theApplicant = $resource->application_log->whereIn('batch_id', $batchesForInspection->pluck('id'))->first();
                     }
                 }
            }
            $batch_id = $theApplicant?->batch_id;
            $region_id = $theApplicant?->region_id;
        }
        $this->canAccessResult()->firstOrCreate([
            'user_id' => $this->id,
            'camp_id' => $camp->id,
            'batch_id' => $batch_id,
            'region_id' => $region_id,
            'accessible_id' => (is_object($target) && property_exists($target, 'id')) ? $target->id : null,
            'accessible_type' => $class,
            'context' => $context,
            'can_access' => $result ? 1 : 0
        ]);
        return $result ? true : false;
    }
    */

    public function getAccessibleResult($resource, $action, $camp, $context = null, $target = null, $probing = null) {
        // Get the pre-loaded/cached context for this camp
        $campContext = $this->getOrLoadCampContext($camp);

        if (!$resource) { // Basic validation
            return false;
        }

        // If $campContext is empty (e.g., $camp was invalid or user has no roles/permissions in camp), return false.
        if (empty($campContext) || $campContext['parsed_permissions']->isEmpty()) {
            // This check was simplified. Review original logic if some access should be granted
            // even without specific parsed_permissions for the camp.
            // For now, if no specific permissions, we proceed to fallback logic if $forInspect is not found.
        }

        // Handle context switching for vcampExport
        if ($context == "vcampExport" && $target && property_exists($target, 'camp') && $target->camp) {
            $targetCampId = is_object($target->camp) ? $target->camp->id : $target->camp; // Get ID safely
            $vCampModel = Vcamp::query()->find($targetCampId); // Vcamp model must be used or imported
            if ($vCampModel && $vCampModel->mainCamp) { // Check if mainCamp relation exists and is loaded
                $camp = $vCampModel->mainCamp;
                $campContext = $this->getOrLoadCampContext($camp); // Reload context for the new camp
            }
        }

        $class = is_string($resource) ? $resource : get_class($resource);
        if ($resource instanceof \App\Models\Volunteer && $context == "vcampExport") {
            $class = "App\\Models\\Applicant";
        }

        $permissionsToInspect = $campContext['parsed_permissions'];

        // Attempt to find the permission rule, trying different class name formats
        $forInspect = $permissionsToInspect->where("resource", $class)->where("action", $action)->first();
        if (!$forInspect) {
            $forInspect = $permissionsToInspect->where("resource", '\\' . $class)->where("action", $action)->first();
        }
        if (!$forInspect) {
             $forInspect = $permissionsToInspect->where("resource", str_replace('App\\Models\\', '', $class))->where("action", $action)->first(); // Try with no namespace if stored that way
        }


        if ($forInspect) {
            if ($probing) {
                dump("Found permission rule:", $forInspect);
            }
            switch ($forInspect['range_parsed']) { // $forInspect is an array from the collection
                case 0: // na, all
                    return true;

                case 1: // volunteer_large_group (大組義工)
                    $userCampRoles = $campContext['camp_roles'];
                    $relevantUserSections = $userCampRoles->pluck('section')->unique()->filter()->all();

                    if (empty($relevantUserSections)) return false;

                    $resourceUser = null;
                    if (method_exists($resource, 'user') && $resource->user) {
                        $resourceUser = $resource->user;
                    } elseif (property_exists($resource, 'user_id') && $resource->user_id) {
                        if (!($resource instanceof User && $resource->id == $this->id)) {
                             $resourceUser = User::find($resource->user_id); // DB query
                        } else {
                             $resourceUser = $resource;
                        }
                    } elseif ($resource instanceof User) { // If $resource itself is a User model
                        $resourceUser = $resource;
                    }

                    if ($resourceUser && method_exists($resourceUser, 'roles')) {
                        if ($resourceUser->is($this)) {
                            return $campContext['camp_roles']
                                           ->whereIn("section", $relevantUserSections)
                                           ->isNotEmpty();
                        }
                        return $resourceUser->roles()
                                       ->where('camp_id', $camp->id)
                                       ->whereIn("section", $relevantUserSections)
                                       ->exists();
                    }
                    return false;

                case 2: // learner_group (學員小組)
                    $userGroupIds = $campContext['user_group_ids'];
                    if (empty($userGroupIds)) return false;

                    if (str_contains($class, "Applicant") && $context == "onlyCheckAvailability") {
                        return true;
                    }

                    $targetGroupId = null;
                    if ($target && property_exists($target, 'group_id') && $target->group_id !== null) {
                        $targetGroupId = $target->group_id;
                    } elseif (str_contains($class, "ApplicantsGroup") && property_exists($resource, 'id')) { // $resource is ApplicantsGroup
                        $targetGroupId = $resource->id;
                    } elseif ($target === null && property_exists($resource, 'group_id') && $resource->group_id !== null) { // $resource is Applicant or similar
                         $targetGroupId = $resource->group_id;
                    }

                    if ($targetGroupId !== null) {
                        return in_array($targetGroupId, $userGroupIds);
                    }

                    if ($target && ($target instanceof \App\Models\User || $target instanceof \App\Models\Volunteer)) {
                        $targetUserToCheck = ($target instanceof \App\Models\Volunteer && method_exists($target, 'user') && $target->user) ? $target->user : $target;
                        if ($targetUserToCheck && method_exists($targetUserToCheck, 'roles')) {
                            $targetUserAssociatedGroupIds = [];
                            if ($targetUserToCheck->is($this)) {
                                $targetUserAssociatedGroupIds = $campContext['user_group_ids'];
                            } else {
                                $targetUserAssociatedGroupIds = $targetUserToCheck->roles()
                                    ->where('camp_id', $camp->id)
                                    ->whereNotNull('group_id')
                                    ->pluck('group_id')->unique()->all();
                            }
                            if (!empty(array_intersect($userGroupIds, $targetUserAssociatedGroupIds))) {
                                return true;
                            }
                        }
                    }
                    if ($probing) {
                        dump("Case 2 (learner_group), targetGroupId not found or no overlap", $targetGroupId, $userGroupIds, $target, $class);
                    }
                    return false;

                case 3: // person (個人 - 關懷的學員)
                    $caredLearnerIds = $campContext['cared_learner_ids_in_camp'];
                    if (empty($caredLearnerIds)) return false;

                    if (str_contains($class, "Applicant") && $context == "onlyCheckAvailability") {
                        return true;
                    }

                    $resourceIdToCheck = null;
                    if ($class == "App\\Models\\ApplicantGroup" && property_exists($resource, 'id')) {
                        // Check if user cares for any learner in the specified group $resource->id
                        // This requires a DB query.
                        $learnersInGroup = Applicant::where('group_id', $resource->id)
                                                 ->whereIn('batch_id', $campContext['user_batch_ids']) // Check against user's relevant batches
                                                 ->pluck('id')->all();
                        return !empty(array_intersect($learnersInGroup, $caredLearnerIds));

                    } elseif ($class == "App\\Models\\Applicant" && property_exists($resource, 'id')) {
                         $resourceIdToCheck = $resource->id;
                    } elseif ($class == "App\\Models\\ContactLog" && $target && property_exists($target, 'id') && $target instanceof Applicant) {
                         // Assuming ContactLog's permission is tied to the target Applicant
                         $resourceIdToCheck = $target->id;
                    }
                    // Add other specific class checks from original logic if needed

                    if ($resourceIdToCheck !== null) {
                        return in_array($resourceIdToCheck, $caredLearnerIds);
                    }
                     if ($probing) {
                        dump("Case 3 (person), resourceIdToCheck not determined", $resourceIdToCheck, $caredLearnerIds, $class, $resource, $target);
                    }
                    return false;
                default:
                     if ($probing) { dump("Default case in switch (no range_parsed match)", $forInspect); }
                    return false;
            }
        }

        if (!$forInspect) { // If no primary permission rule matched
            if ($target && ((str_contains($class, "Applicant") || str_contains($class, "Volunteer")) && $action == "read")) {
                if ($probing) {
                    dump("Fallback: second if (original logic for Applicant/Volunteer read)", $resource, $action, $camp, $context, $target);
                }
                return false;
            }
            elseif ($target && ($target instanceof User) && (str_contains($class, "User") && ($context == "vcamp" || $context == "vcampExport") && $action == "read")) {
                $thisUserCampRoles = $campContext['camp_roles'];

                $theApplicant = null;
                if (method_exists($target, 'application_log') && $camp->vcamp) {
                    $targetApplicationLogRelation = $target->application_log();
                    if ($targetApplicationLogRelation instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                        // Ensure vcamp->batchs is available. It might be loaded if $camp->vcamp was accessed.
                        // If $camp->vcamp->batchs is a relation, it will trigger a query here if not loaded.
                        $vcampBatches = $camp->vcamp->batchs; // Assumes 'batchs' is an accessible relation/property
                        if ($vcampBatches && !$vcampBatches->isEmpty()) {
                           $theApplicant = $targetApplicationLogRelation->whereIn('batch_id', $vcampBatches->pluck('id'))->first();
                        } else if ($probing) {
                            dump("Fallback: User read in vcamp - $camp->vcamp has no batches or batches relation is empty.", $camp->vcamp);
                        }
                    }
                }

                if (!$theApplicant) {
                    if ($probing) dump("Fallback: User read in vcamp - target's applicant profile not found in vcamp batches.", $target, $camp->vcamp);
                    return false;
                }

                $targetUserRolesInCamp = null;
                if ($target->is($this)) {
                    $targetUserRolesInCamp = $thisUserCampRoles;
                } else {
                    $targetUserRolesInCamp = $target->roles()->where("camp_id", $camp->id)->get();
                }

                if ($probing) {
                    dump("Fallback: User read in vcamp (refactored path)", $class, $action, $context, $target, $thisUserCampRoles, $theApplicant, $targetUserRolesInCamp);
                }

                $result = $thisUserCampRoles->some(function ($role) use ($theApplicant, $targetUserRolesInCamp) {
                    $applicantRegionId = $theApplicant->region_id ?? null;

                    $condition1 = $role->region_id && $applicantRegionId && $role->region_id == $applicantRegionId;

                    $condition2 = false;
                    if ($role->group_id && $targetUserRolesInCamp && !$targetUserRolesInCamp->isEmpty()) {
                        $condition2 = $targetUserRolesInCamp->firstWhere('group_id', $role->group_id) !== null;
                    }

                    $condition3 = false;
                    if ($role->region_id && $targetUserRolesInCamp && !$targetUserRolesInCamp->isEmpty()) {
                        $condition3 = $targetUserRolesInCamp->firstWhere('region_id', $role->region_id) !== null;
                    }

                    return $condition1 || $condition2 || $condition3;
                });
                return $result;
            }
        }


        if ($probing) {
            error_log("User::getAccessibleResult: Fallback - No specific permission rule matched or condition met. Class: $class, Action: $action, Context: $context");
        }
        return false; // Default fallback
    }

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }
}
