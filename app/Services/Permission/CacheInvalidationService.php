<?php

namespace App\Services\Permission;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Camp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CacheInvalidationService
{
    private PermissionCache $permissionCache;

    public function __construct(PermissionCache $permissionCache)
    {
        $this->permissionCache = $permissionCache;
    }

    /**
     * 當使用者角色變更時清除快取
     */
    public function invalidateUserRoleCache(User $user, ?Camp $camp = null): void
    {
        if ($camp) {
            // 清除特定營隊的權限快取
            $this->invalidateUserCampCache($user, $camp);
        } else {
            // 清除使用者的所有權限快取
            $this->invalidateAllUserCache($user);
        }

        Log::info('User role cache invalidated', [
            'user_id' => $user->id,
            'camp_id' => $camp?->id,
            'scope' => $camp ? 'camp_specific' : 'all_camps'
        ]);
    }

    /**
     * 當權限設定變更時清除快取
     */
    public function invalidatePermissionCache(Permission $permission): void
    {
        // 找到所有使用此權限的角色
        $affectedRoles = $permission->roles()->get();
        
        foreach ($affectedRoles as $role) {
            $this->invalidateRoleCache($role);
        }

        Log::info('Permission cache invalidated', [
            'permission_id' => $permission->id,
            'affected_roles' => $affectedRoles->pluck('id')->toArray()
        ]);
    }

    /**
     * 當角色設定變更時清除快取
     */
    public function invalidateRoleCache(Role $role): void
    {
        // 找到所有擁有此角色的使用者
        $affectedUsers = $role->users()->get();
        
        foreach ($affectedUsers as $user) {
            if ($role->camp_id) {
                // 營隊特定角色
                $camp = $role->camp;
                if ($camp) {
                    $this->invalidateUserCampCache($user, $camp);
                }
            } else {
                // 全域角色
                $this->invalidateAllUserCache($user);
            }
        }

        Log::info('Role cache invalidated', [
            'role_id' => $role->id,
            'camp_id' => $role->camp_id,
            'affected_users' => $affectedUsers->pluck('id')->toArray()
        ]);
    }

    /**
     * 當營隊設定變更時清除快取
     */
    public function invalidateCampCache(Camp $camp): void
    {
        // 找到所有與此營隊相關的使用者
        $affectedUsers = User::whereHas('roles', function ($query) use ($camp) {
            $query->where('camp_id', $camp->id);
        })->get();

        foreach ($affectedUsers as $user) {
            $this->invalidateUserCampCache($user, $camp);
        }

        // 也清除營隊相關的快取
        $this->invalidateCampSpecificCache($camp);

        Log::info('Camp cache invalidated', [
            'camp_id' => $camp->id,
            'affected_users' => $affectedUsers->pluck('id')->toArray()
        ]);
    }

    /**
     * 清除使用者在特定營隊的快取
     */
    public function invalidateUserCampCache(User $user, Camp $camp): void
    {
        // 清除使用者權限快取
        $userPermissionsCacheKey = $this->permissionCache->getUserPermissionsCacheKey($user, $camp);
        Cache::forget($userPermissionsCacheKey);

        // 清除使用者的所有權限檢查快取（使用 pattern 匹配）
        $this->clearUserPermissionPatterns($user, $camp);

        Log::debug('User camp cache invalidated', [
            'user_id' => $user->id,
            'camp_id' => $camp->id
        ]);
    }

    /**
     * 清除使用者的所有快取
     */
    public function invalidateAllUserCache(User $user): void
    {
        // 找到使用者參與的所有營隊
        $camps = Camp::whereHas('roles.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();

        foreach ($camps as $camp) {
            $this->invalidateUserCampCache($user, $camp);
        }

        Log::debug('All user cache invalidated', [
            'user_id' => $user->id,
            'camps_count' => $camps->count()
        ]);
    }

    /**
     * 清除營隊特定的快取
     */
    private function invalidateCampSpecificCache(Camp $camp): void
    {
        $patterns = [
            PermissionConstants::CACHE_PREFIX . '*_camp_' . $camp->id . '_*',
            'camp_data_' . $camp->id,
            'camp_roles_' . $camp->id
        ];

        foreach ($patterns as $pattern) {
            $this->clearCacheByPattern($pattern);
        }
    }

    /**
     * 使用 Redis 的 pattern 清除快取
     */
    private function clearCacheByPattern(string $pattern): void
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Redis::connection();
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                    Log::debug('Cache cleared by pattern', [
                        'pattern' => $pattern,
                        'keys_count' => count($keys)
                    ]);
                }
            } else {
                // 對於非 Redis 快取，執行全面清除
                Cache::flush();
                Log::warning('Non-Redis cache detected, performed full cache flush', [
                    'pattern' => $pattern
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear cache by pattern', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
            
            // 失敗時執行全面清除
            Cache::flush();
        }
    }

    /**
     * 清除使用者權限檢查快取的 patterns
     */
    private function clearUserPermissionPatterns(User $user, Camp $camp): void
    {
        $patterns = [
            PermissionConstants::CACHE_PREFIX . '*_user_' . $user->id . '_*',
            PermissionConstants::CACHE_PREFIX . '*_camp_' . $camp->id . '_*'
        ];

        foreach ($patterns as $pattern) {
            $this->clearCacheByPattern($pattern);
        }
    }

    /**
     * 批次清除多個使用者的快取
     */
    public function invalidateMultipleUsers(array $userIds, ?Camp $camp = null): void
    {
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if ($camp) {
                $this->invalidateUserCampCache($user, $camp);
            } else {
                $this->invalidateAllUserCache($user);
            }
        }

        Log::info('Multiple users cache invalidated', [
            'user_ids' => $userIds,
            'camp_id' => $camp?->id,
            'users_count' => count($userIds)
        ]);
    }

    /**
     * 預熱快取 - 在權限變更後重新載入常用權限
     */
    public function warmupCache(User $user, Camp $camp): void
    {
        try {
            // 重新載入使用者權限
            $user->getCachedPermissions($camp);

            Log::info('Cache warmed up', [
                'user_id' => $user->id,
                'camp_id' => $camp->id
            ]);
        } catch (\Exception $e) {
            Log::error('Cache warmup failed', [
                'user_id' => $user->id,
                'camp_id' => $camp->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * 獲取快取統計資訊
     */
    public function getCacheStats(): array
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Redis::connection();
                
                $permissionKeys = $redis->keys(PermissionConstants::CACHE_PREFIX . '*');
                $userPermissionKeys = $redis->keys(PermissionConstants::CACHE_PREFIX . 'user_permissions_*');
                
                return [
                    'total_permission_cache_keys' => count($permissionKeys),
                    'user_permission_cache_keys' => count($userPermissionKeys),
                    'cache_store' => 'redis',
                    'timestamp' => now()->toISOString()
                ];
            }
            
            return [
                'cache_store' => 'non-redis',
                'message' => 'Detailed stats not available for non-Redis cache',
                'timestamp' => now()->toISOString()
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Failed to get cache stats: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ];
        }
    }
}