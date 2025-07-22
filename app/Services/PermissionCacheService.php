<?php

namespace App\Services;

use App\Models\User;
use App\Models\Camp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class PermissionCacheService
{
    // 快取時間設為 5 分鐘，因為權限可能會頻繁變動
    private const CACHE_TTL = 300; // 5 minutes

    /**
     * 預熱使用者在特定營隊的權限資料
     */
    public function warmupUserPermissions(User $user, Camp $camp): void
    {
        $cacheKey = $this->getUserPermissionsCacheKey($user->id, $camp->id);

        // 預載入所有相關資料
        $permissions = $user->roles()
            ->where('camp_id', $camp->id)
            ->with(['permissions'])
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'resource' => $permission->resource,
                    'action' => $permission->action,
                    'range' => $permission->range,
                    'range_parsed' => $permission->range_parsed,
                    'batch_id' => $permission->batch_id,
                    'region_id' => $permission->region_id,
                ];
            })
            ->groupBy('resource');

        Cache::put($cacheKey, $permissions, self::CACHE_TTL);
    }

    /**
     * 取得使用者在特定營隊的權限資料
     */
    public function getUserPermissions(User $user, Camp $camp): Collection
    {
        $cacheKey = $this->getUserPermissionsCacheKey($user->id, $camp->id);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $camp) {
            return $user->roles()
                ->where('camp_id', $camp->id)
                ->with(['permissions'])
                ->get()
                ->pluck('permissions')
                ->flatten()
                ->unique('id')
                ->groupBy('resource');
        });
    }

    /**
     * 清除使用者的權限快取
     */
    public function clearUserPermissions(User $user, Camp $camp = null): void
    {
        if ($camp) {
            $cacheKey = $this->getUserPermissionsCacheKey($user->id, $camp->id);
            Cache::forget($cacheKey);
        } else {
            // 清除該使用者的所有營隊權限快取
            // 使用標籤功能來管理相關快取（需要 Redis 或 Memcached）
            Cache::tags(['user_permissions', "user_{$user->id}"])->flush();
        }
    }

    /**
     * 預載入使用者的關聯資料以提升查詢效能
     */
    public function preloadUserRelations(User $user, Camp $camp): void
    {
        $cacheKey = "user_relations_{$user->id}_{$camp->id}";

        Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $camp) {
            // 預載入角色資料
            $roles = $user->roles()
                ->where('camp_id', $camp->id)
                ->with(['permissions'])
                ->get();

            // 預載入關懷學員資料
            $caresLearners = $user->caresLearners()
                ->whereIn('batch_id', $camp->batchs->pluck('id'))
                ->select('id', 'batch_id', 'region_id', 'group_id')
                ->get();

            return [
                'roles' => $roles,
                'cares_learners' => $caresLearners,
                'role_sections' => $roles->pluck('section')->filter()->unique(),
                'role_group_ids' => $roles->pluck('group_id')->filter()->unique(),
                'role_region_ids' => $roles->pluck('region_id')->filter()->unique(),
            ];
        });
    }

    /**
     * 批次預載入多個使用者的權限
     */
    public function warmupMultipleUserPermissions(Collection $users, Camp $camp): void
    {
        $users->each(function ($user) use ($camp) {
            $this->warmupUserPermissions($user, $camp);
        });
    }

    private function getUserPermissionsCacheKey(int $userId, int $campId): string
    {
        return "user_permissions_{$userId}_{$campId}";
    }
}
