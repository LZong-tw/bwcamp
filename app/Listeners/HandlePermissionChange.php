<?php

namespace App\Listeners;

use App\Events\PermissionChanged;
use App\Services\Permission\CacheInvalidationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandlePermissionChange implements ShouldQueue
{
    use InteractsWithQueue;

    private CacheInvalidationService $cacheInvalidation;

    /**
     * Create the event listener.
     */
    public function __construct(CacheInvalidationService $cacheInvalidation)
    {
        $this->cacheInvalidation = $cacheInvalidation;
    }

    /**
     * Handle the event.
     */
    public function handle(PermissionChanged $event): void
    {
        try {
            // 清除相關快取
            $this->cacheInvalidation->invalidateUserRoleCache($event->user, $event->camp);

            // 預熱快取（如果有指定營隊）
            if ($event->camp) {
                $this->cacheInvalidation->warmupCache($event->user, $event->camp);
            }

            Log::info('Permission change handled', [
                'user_id' => $event->user->id,
                'camp_id' => $event->camp?->id,
                'change_type' => $event->changeType,
                'change_details' => $event->changeDetails
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to handle permission change', [
                'user_id' => $event->user->id,
                'camp_id' => $event->camp?->id,
                'change_type' => $event->changeType,
                'error' => $e->getMessage()
            ]);

            // 重新拋出異常以觸發重試機制
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(PermissionChanged $event, \Throwable $exception): void
    {
        Log::error('Permission change handler failed permanently', [
            'user_id' => $event->user->id,
            'camp_id' => $event->camp?->id,
            'change_type' => $event->changeType,
            'error' => $exception->getMessage()
        ]);
    }
}