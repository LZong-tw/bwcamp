<?php

namespace App\Services\Permission;

use App\Contracts\PermissionChecker;
use App\Models\User;
use App\Services\Permission\Strategies\AllAccessStrategy;
use App\Services\Permission\Strategies\GroupAccessStrategy;
use App\Services\Permission\Strategies\PersonalAccessStrategy;
use Illuminate\Support\Facades\Log;

class PermissionFactory
{
    public function __construct()
    {
        // Factory doesn't need cache directly
    }

    /**
     * Create appropriate permission checker based on user's permission level
     */
    public function createChecker(User $user, $resource, string $action, $camp, array $context = []): PermissionChecker
    {
        $permissionLevel = $this->getPermissionLevel($user, $resource, $action, $camp, $context);
        
        switch ($permissionLevel) {
            case PermissionConstants::RANGE_ALL:
                return new AllAccessStrategy();
            
            case PermissionConstants::RANGE_VOLUNTEER_LARGE_GROUP:
            case PermissionConstants::RANGE_LEARNER_GROUP:
                return new GroupAccessStrategy();
            
            case PermissionConstants::RANGE_PERSON:
                return new PersonalAccessStrategy();
            
            default:
                Log::warning('Unknown permission level', [
                    'user_id' => $user->id,
                    'resource' => is_string($resource) ? $resource : get_class($resource),
                    'action' => $action,
                    'camp_id' => $camp->id,
                    'level' => $permissionLevel
                ]);
                return new PersonalAccessStrategy(); // Default to most restrictive
        }
    }

    /**
     * Get permission level for user
     */
    private function getPermissionLevel(User $user, $resource, string $action, $camp, array $context = []): ?int
    {
        $resourceClass = is_string($resource) ? $resource : get_class($resource);
        
        // Handle vcampExport context
        if ($resourceClass === 'App\Models\Volunteer' && ($context['context'] ?? null) === PermissionConstants::CONTEXT_VCAMP_EXPORT) {
            $resourceClass = 'App\Models\Applicant';
        }

        // Get cached permissions
        $permissions = $user->getCachedPermissions($camp);
        $permission = $permissions->where('resource', '\\' . $resourceClass)->where('action', $action)->first();

        return $permission ? $permission->range_parsed : null;
    }

    /**
     * Create checker for batch operations
     */
    public function createBatchChecker(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $checkers = [];
        
        // Group resources by permission level for optimization
        $groupedResources = [];
        foreach ($resources as $key => $resource) {
            $level = $this->getPermissionLevel($user, $resource, $action, $camp, $context);
            $groupedResources[$level][$key] = $resource;
        }
        
        // Create checkers for each permission level
        foreach ($groupedResources as $level => $levelResources) {
            $checker = $this->createCheckerByLevel($level);
            $checkers[$level] = [
                'checker' => $checker,
                'resources' => $levelResources
            ];
        }
        
        return $checkers;
    }

    /**
     * Create checker by permission level
     */
    private function createCheckerByLevel(?int $level): PermissionChecker
    {
        switch ($level) {
            case PermissionConstants::RANGE_ALL:
                return new AllAccessStrategy();
            
            case PermissionConstants::RANGE_VOLUNTEER_LARGE_GROUP:
            case PermissionConstants::RANGE_LEARNER_GROUP:
                return new GroupAccessStrategy();
            
            case PermissionConstants::RANGE_PERSON:
                return new PersonalAccessStrategy();
            
            default:
                return new PersonalAccessStrategy(); // Default to most restrictive
        }
    }
}