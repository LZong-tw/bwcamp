<?php

namespace App\Services\Permission;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PermissionCache
{
    /**
     * Generate cache key for permission check
     */
    public function getCacheKey(User $user, $resource, string $action, $camp, array $context = []): string
    {
        $resourceId = is_object($resource) ? $resource->id ?? 'null' : 'class';
        $resourceClass = is_string($resource) ? $resource : get_class($resource);
        $contextHash = md5(json_encode($context));
        
        return PermissionConstants::CACHE_PREFIX . md5(
            $user->id . '_' . 
            $resourceClass . '_' . 
            $resourceId . '_' . 
            $action . '_' . 
            $camp->id . '_' . 
            $contextHash
        );
    }

    /**
     * Get cached permission result
     */
    public function get(string $cacheKey): ?bool
    {
        $result = Cache::get($cacheKey);
        
        if ($result !== null) {
            Log::debug('Permission cache hit', ['key' => $cacheKey, 'result' => $result]);
        }
        
        return $result;
    }

    /**
     * Store permission result in cache
     */
    public function put(string $cacheKey, bool $result): void
    {
        Cache::put($cacheKey, $result, PermissionConstants::CACHE_TTL);
        Log::debug('Permission cached', ['key' => $cacheKey, 'result' => $result]);
    }

    /**
     * Clear permission cache for a user
     */
    public function clearUserCache(User $user): void
    {
        // Since we use hashed keys, we need to clear all permission cache
        // In a production environment, you might want to use cache tags
        Cache::flush();
        Log::info('Permission cache cleared for user', ['user_id' => $user->id]);
    }

    /**
     * Clear permission cache for a camp
     */
    public function clearCampCache($camp): void
    {
        Cache::flush();
        Log::info('Permission cache cleared for camp', ['camp_id' => $camp->id]);
    }

    /**
     * Get cache key for user permissions
     */
    public function getUserPermissionsCacheKey(User $user, $camp): string
    {
        return PermissionConstants::CACHE_PREFIX . 'user_permissions_' . $user->id . '_' . $camp->id;
    }

    /**
     * Get cached user permissions
     */
    public function getUserPermissions(User $user, $camp)
    {
        $cacheKey = $this->getUserPermissionsCacheKey($user, $camp);
        return Cache::get($cacheKey);
    }

    /**
     * Cache user permissions
     */
    public function cacheUserPermissions(User $user, $camp, $permissions): void
    {
        $cacheKey = $this->getUserPermissionsCacheKey($user, $camp);
        Cache::put($cacheKey, $permissions, PermissionConstants::CACHE_TTL);
        Log::debug('User permissions cached', ['user_id' => $user->id, 'camp_id' => $camp->id]);
    }
}