<?php

namespace App\Observers;

use App\Models\User;
use App\Services\Permission\CacheInvalidationService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    private CacheInvalidationService $cacheInvalidation;

    public function __construct(CacheInvalidationService $cacheInvalidation)
    {
        $this->cacheInvalidation = $cacheInvalidation;
    }

    /**
     * Handle the User "updated" event.
     * 只在使用者狀態相關欄位變更時清除快取
     */
    public function updated(User $user): void
    {
        // 檢查是否有影響權限的欄位變更
        $permissionRelevantFields = ['email', 'status', 'active'];
        
        $hasRelevantChanges = false;
        foreach ($permissionRelevantFields as $field) {
            if ($user->isDirty($field)) {
                $hasRelevantChanges = true;
                break;
            }
        }

        if ($hasRelevantChanges) {
            $this->invalidateUserCache($user, 'updated');
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->invalidateUserCache($user, 'deleted');
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->invalidateUserCache($user, 'restored');
    }

    /**
     * Invalidate cache for user changes
     */
    private function invalidateUserCache(User $user, string $action): void
    {
        try {
            $this->cacheInvalidation->invalidateAllUserCache($user);
            
            Log::info('User cache invalidated via observer', [
                'user_id' => $user->id,
                'action' => $action
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to invalidate user cache in observer', [
                'user_id' => $user->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }
}