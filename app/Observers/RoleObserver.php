<?php

namespace App\Observers;

use App\Models\Role;
use App\Services\Permission\CacheInvalidationService;
use Illuminate\Support\Facades\Log;

class RoleObserver
{
    private CacheInvalidationService $cacheInvalidation;

    public function __construct(CacheInvalidationService $cacheInvalidation)
    {
        $this->cacheInvalidation = $cacheInvalidation;
    }

    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        $this->invalidateRoleCache($role, 'created');
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $this->invalidateRoleCache($role, 'updated');
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $this->invalidateRoleCache($role, 'deleted');
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        $this->invalidateRoleCache($role, 'restored');
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        $this->invalidateRoleCache($role, 'force_deleted');
    }

    /**
     * Invalidate cache for role changes
     */
    private function invalidateRoleCache(Role $role, string $action): void
    {
        try {
            $this->cacheInvalidation->invalidateRoleCache($role);
            
            Log::info('Role cache invalidated via observer', [
                'role_id' => $role->id,
                'camp_id' => $role->camp_id,
                'action' => $action,
                'role_name' => $role->name
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to invalidate role cache in observer', [
                'role_id' => $role->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }
}