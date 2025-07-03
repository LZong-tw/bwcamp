<?php

namespace App\Contracts;

use App\Models\User;

interface PermissionChecker
{
    /**
     * Check if user can access a resource
     */
    public function canAccess(User $user, $resource, string $action, $camp, array $context = []): bool;

    /**
     * Check multiple resources at once for better performance
     */
    public function canAccessBatch(User $user, array $resources, string $action, $camp, array $context = []): array;

    /**
     * Get permission level for debugging
     */
    public function getPermissionLevel(User $user, $resource, string $action, $camp, array $context = []): ?int;
}