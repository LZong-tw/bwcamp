<?php

namespace App\Services\Permission;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class EnhancedPermissionService
{
    private PermissionCache $cache;
    private PermissionFactory $factory;

    public function __construct(PermissionCache $cache, PermissionFactory $factory)
    {
        $this->cache = $cache;
        $this->factory = $factory;
    }

    /**
     * Enhanced permission check with caching
     */
    public function canAccessResource(User $user, $resource, string $action, $camp, array $context = []): bool
    {
        // Handle null resource
        if (!$resource) {
            return false;
        }

        // Generate cache key
        $cacheKey = $this->cache->getCacheKey($user, $resource, $action, $camp, $context);
        
        // Check cache first
        $cachedResult = $this->cache->get($cacheKey);
        if ($cachedResult !== null) {
            return $cachedResult;
        }

        try {
            // Create appropriate checker
            $checker = $this->factory->createChecker($user, $resource, $action, $camp, $context);
            
            // Perform permission check
            $result = $checker->canAccess($user, $resource, $action, $camp, $context);
            
            // Cache the result
            $this->cache->put($cacheKey, $result);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Permission check failed', [
                'user_id' => $user->id,
                'resource' => is_string($resource) ? $resource : get_class($resource),
                'action' => $action,
                'camp_id' => $camp->id,
                'error' => $e->getMessage()
            ]);
            
            return false; // Fail closed
        }
    }

    /**
     * Batch permission check for better performance
     */
    public function canAccessResourceBatch(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $results = [];
        $uncachedResources = [];
        
        // Check cache for each resource
        foreach ($resources as $key => $resource) {
            $cacheKey = $this->cache->getCacheKey($user, $resource, $action, $camp, $context);
            $cachedResult = $this->cache->get($cacheKey);
            
            if ($cachedResult !== null) {
                $results[$key] = $cachedResult;
            } else {
                $uncachedResources[$key] = $resource;
            }
        }
        
        // Process uncached resources
        if (!empty($uncachedResources)) {
            try {
                $checkers = $this->factory->createBatchChecker($user, $uncachedResources, $action, $camp, $context);
                
                foreach ($checkers as $checkerData) {
                    $checker = $checkerData['checker'];
                    $levelResources = $checkerData['resources'];
                    
                    $levelResults = $checker->canAccessBatch($user, $levelResources, $action, $camp, $context);
                    
                    // Cache and merge results
                    foreach ($levelResults as $key => $result) {
                        $resource = $levelResources[$key];
                        $cacheKey = $this->cache->getCacheKey($user, $resource, $action, $camp, $context);
                        $this->cache->put($cacheKey, $result);
                        $results[$key] = $result;
                    }
                }
                
            } catch (\Exception $e) {
                Log::error('Batch permission check failed', [
                    'user_id' => $user->id,
                    'action' => $action,
                    'camp_id' => $camp->id,
                    'resource_count' => count($uncachedResources),
                    'error' => $e->getMessage()
                ]);
                
                // Fail closed for uncached resources
                foreach ($uncachedResources as $key => $resource) {
                    $results[$key] = false;
                }
            }
        }
        
        return $results;
    }

    /**
     * Get permission level for debugging
     */
    public function getPermissionLevel(User $user, $resource, string $action, $camp, array $context = []): ?int
    {
        try {
            $checker = $this->factory->createChecker($user, $resource, $action, $camp, $context);
            return $checker->getPermissionLevel($user, $resource, $action, $camp, $context);
        } catch (\Exception $e) {
            Log::error('Get permission level failed', [
                'user_id' => $user->id,
                'resource' => is_string($resource) ? $resource : get_class($resource),
                'action' => $action,
                'camp_id' => $camp->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Clear cache for user
     */
    public function clearUserCache(User $user): void
    {
        $this->cache->clearUserCache($user);
    }

    /**
     * Clear cache for camp
     */
    public function clearCampCache($camp): void
    {
        $this->cache->clearCampCache($camp);
    }
}