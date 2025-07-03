<?php

namespace App\Services\Permission\Strategies;

use App\Contracts\PermissionChecker;
use App\Models\User;
use App\Services\Permission\PermissionConstants;

class PersonalAccessStrategy implements PermissionChecker
{
    public function canAccess(User $user, $resource, string $action, $camp, array $context = []): bool
    {
        $resourceClass = is_string($resource) ? $resource : get_class($resource);
        $contextValue = $context['context'] ?? null;
        $target = $context['target'] ?? null;

        // Handle availability check
        if (str_contains($resourceClass, 'Applicant') && $contextValue === PermissionConstants::CONTEXT_ONLY_CHECK_AVAILABILITY) {
            return $user->caresLearners()->whereIn('batch_id', $camp->batchs->pluck('id'))->exists();
        }

        // Handle specific resource types
        switch ($resourceClass) {
            case 'App\Models\ApplicantGroup':
                return $user->caresLearners()
                    ->where('group_id', '<>', null)
                    ->where('group_id', $resource->id)
                    ->exists();

            case 'App\Models\CampOrg':
                return false; // No personal access to camp org

            case 'App\Models\Applicant':
                return $user->caresLearners()
                    ->where('group_id', '<>', null)
                    ->where('id', $resource->id)
                    ->exists();

            case 'App\Models\ContactLog':
                return $user->caresLearners()
                    ->where('group_id', '<>', null)
                    ->where('id', $target->id)
                    ->exists();

            default:
                return false;
        }
    }

    public function canAccessBatch(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $results = [];
        
        // Optimize for batch personal access checking
        $caresLearners = $user->caresLearners()
            ->where('group_id', '<>', null)
            ->whereIn('batch_id', $camp->batchs->pluck('id'))
            ->get();
        
        foreach ($resources as $key => $resource) {
            $resourceClass = is_string($resource) ? $resource : get_class($resource);
            
            switch ($resourceClass) {
                case 'App\Models\Applicant':
                    $results[$key] = $caresLearners->where('id', $resource->id)->count() > 0;
                    break;
                
                default:
                    $results[$key] = $this->canAccess($user, $resource, $action, $camp, $context);
            }
        }
        
        return $results;
    }

    public function getPermissionLevel(User $user, $resource, string $action, $camp, array $context = []): ?int
    {
        return PermissionConstants::RANGE_PERSON;
    }
}