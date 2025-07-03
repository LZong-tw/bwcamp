<?php

namespace App\Services\Permission\Strategies;

use App\Contracts\PermissionChecker;
use App\Models\User;
use App\Services\Permission\PermissionConstants;

class AllAccessStrategy implements PermissionChecker
{
    public function canAccess(User $user, $resource, string $action, $camp, array $context = []): bool
    {
        // All access - always return true
        return true;
    }

    public function canAccessBatch(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        // All access - return true for all resources
        return array_fill_keys(array_keys($resources), true);
    }

    public function getPermissionLevel(User $user, $resource, string $action, $camp, array $context = []): ?int
    {
        return PermissionConstants::RANGE_ALL;
    }
}