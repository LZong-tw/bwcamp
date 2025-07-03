<?php

namespace App\Observers;

use App\Models\Permission;
use App\Services\Permission\CacheInvalidationService;
use Illuminate\Support\Facades\Log;

class PermissionObserver
{
    private CacheInvalidationService $cacheInvalidation;

    public function __construct(CacheInvalidationService $cacheInvalidation)
    {
        $this->cacheInvalidation = $cacheInvalidation;
    }

    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $this->invalidatePermissionCache($permission, 'created');
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        $this->invalidatePermissionCache($permission, 'updated');
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        $this->invalidatePermissionCache($permission, 'deleted');
    }

    /**
     * Handle the Permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        $this->invalidatePermissionCache($permission, 'restored');
    }

    /**
     * Handle the Permission "force deleted" event.
     */
    public function forceDeleted(Permission $permission): void
    {
        $this->invalidatePermissionCache($permission, 'force_deleted');
    }

    /**
     * Invalidate cache for permission changes
     */
    private function invalidatePermissionCache(Permission $permission, string $action): void
    {
        try {
            $this->cacheInvalidation->invalidatePermissionCache($permission);
            
            Log::info('Permission cache invalidated via observer', [
                'permission_id' => $permission->id,
                'action' => $action,
                'permission_name' => $permission->name,
                'resource' => $permission->resource
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to invalidate permission cache in observer', [
                'permission_id' => $permission->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }
}