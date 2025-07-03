<?php

namespace App\Services\Permission\Strategies;

use App\Contracts\PermissionChecker;
use App\Models\User;
use App\Services\Permission\PermissionConstants;

class GroupAccessStrategy implements PermissionChecker
{
    public function canAccess(User $user, $resource, string $action, $camp, array $context = []): bool
    {
        $resourceClass = is_string($resource) ? $resource : get_class($resource);
        $target = $context['target'] ?? null;

        // Handle volunteer large group access
        if ($this->getPermissionLevel($user, $resource, $action, $camp, $context) === PermissionConstants::RANGE_VOLUNTEER_LARGE_GROUP) {
            return $this->checkVolunteerLargeGroupAccess($user, $resource, $camp, $resourceClass);
        }

        // Handle learner group access
        if ($this->getPermissionLevel($user, $resource, $action, $camp, $context) === PermissionConstants::RANGE_LEARNER_GROUP) {
            return $this->checkLearnerGroupAccess($user, $resource, $camp, $resourceClass, $target, $context);
        }

        return false;
    }

    public function canAccessBatch(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $results = [];
        foreach ($resources as $key => $resource) {
            $results[$key] = $this->canAccess($user, $resource, $action, $camp, $context);
        }
        return $results;
    }

    public function getPermissionLevel(User $user, $resource, string $action, $camp, array $context = []): ?int
    {
        $resourceClass = is_string($resource) ? $resource : get_class($resource);
        if ($resourceClass === 'App\Models\Volunteer' && ($context['context'] ?? null) === PermissionConstants::CONTEXT_VCAMP_EXPORT) {
            $resourceClass = 'App\Models\Applicant';
        }

        $permissions = $user->getCachedPermissions($camp);
        $permission = $permissions->where('resource', '\\' . $resourceClass)->where('action', $action)->first();

        $level = $permission ? $permission->range_parsed : null;
        
        // Only return group-level permissions
        if ($level === PermissionConstants::RANGE_VOLUNTEER_LARGE_GROUP || $level === PermissionConstants::RANGE_LEARNER_GROUP) {
            return $level;
        }
        
        return null;
    }

    private function checkVolunteerLargeGroupAccess(User $user, $resource, $camp, string $resourceClass): bool
    {
        $userSections = $user->roles()->where('camp_id', $camp->id)->pluck('section');

        switch ($resourceClass) {
            case 'App\Models\Volunteer':
                return $resource->user?->roles?->whereIn('section', $userSections)->count() > 0;
            
            case 'App\Models\Applicant':
                return $resource->user?->roles?->whereIn('section', $userSections)->count() > 0;
            
            case 'App\Models\User':
            case 'App\User':
                return $resource->roles?->whereIn('section', $userSections)->count() > 0;
            
            default:
                return false;
        }
    }

    private function checkLearnerGroupAccess(User $user, $resource, $camp, string $resourceClass, $target, array $context): bool
    {
        $userRoles = $user->roles()->where('group_id', '<>', null)->where('camp_id', $camp->id);
        $contextValue = $context['context'] ?? null;

        // Handle availability check
        if (str_contains($resourceClass, 'Applicant') && $contextValue === PermissionConstants::CONTEXT_ONLY_CHECK_AVAILABILITY) {
            return $userRoles->exists();
        }

        // Handle specific resource access
        if (str_contains($resourceClass, 'Applicant') && !str_contains($resourceClass, 'Group') && $target) {
            return $userRoles->where('group_id', $target->group_id)->exists();
        }

        if (str_contains($resourceClass, 'Volunteer') && $target) {
            return $this->checkVolunteerGroupAccess($user, $target, $camp, $userRoles);
        }

        if (str_contains($resourceClass, 'User') && $target) {
            return $this->checkUserGroupAccess($user, $target, $camp, $userRoles);
        }

        // Handle specific resource types
        switch ($resourceClass) {
            case 'App\Models\ContactLog':
                return $userRoles->where('group_id', $target->group_id)->exists();
            
            case 'App\Models\ApplicantsGroup':
                return $userRoles->where('group_id', $target?->group_id ?? $resource->id)->exists();
            
            default:
                return false;
        }
    }

    private function checkVolunteerGroupAccess(User $user, $target, $camp, $userRoles): bool
    {
        $targetGroupId = $target->user?->roles()
            ->where('position', 'like', PermissionConstants::POSITION_CARING_GROUP)
            ->where('camp_id', $camp->id)
            ->value('group_id');

        if (!$targetGroupId) {
            return false;
        }

        // Check direct group access
        if ($userRoles->where('group_id', $targetGroupId)->exists()) {
            return true;
        }

        // Check all_group access for caring positions
        return $user->roles()
            ->where('camp_id', $camp->id)
            ->where('all_group', 1)
            ->where(function ($query) {
                $query->where('position', 'like', PermissionConstants::POSITION_CARING_GROUP)
                    ->orWhere('position', 'like', PermissionConstants::POSITION_CARING_SERVICE)
                    ->orWhere('position', 'like', PermissionConstants::POSITION_CARING_SHORT);
            })
            ->exists();
    }

    private function checkUserGroupAccess(User $user, $target, $camp, $userRoles): bool
    {
        $targetGroupId = $target->roles()
            ->where('position', 'like', PermissionConstants::POSITION_CARING_GROUP)
            ->where('camp_id', $camp->id)
            ->value('group_id');

        if (!$targetGroupId) {
            return false;
        }

        // Check direct group access
        if ($userRoles->where('group_id', $targetGroupId)->exists()) {
            return true;
        }

        // Check all_group access for caring positions
        return $user->roles()
            ->where('camp_id', $camp->id)
            ->where('all_group', 1)
            ->where(function ($query) {
                $query->where('position', 'like', PermissionConstants::POSITION_CARING_GROUP)
                    ->orWhere('position', 'like', PermissionConstants::POSITION_CARING_SERVICE)
                    ->orWhere('position', 'like', PermissionConstants::POSITION_CARING_SHORT);
            })
            ->exists();
    }
}