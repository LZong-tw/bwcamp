<?php

namespace App\Observers;

use App\Models\Batch;
use App\Services\BackendService;

class BatchObserver
{
    public $afterCommit = true;

    public function __construct(public BackendService $backendService)
    {
        //
    }

    public function boot()
    {
        Batch::observe(BatchObserver::class);
    }

    /**
     * Handle the Batch "created" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function created(Batch $batch)
    {
        //
        $this->doGroupCreation($batch);
    }

    /**
     * Handle the Batch "updated" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function updated(Batch $batch)
    {
        //
        $this->doGroupCreation($batch);
    }

    private function doGroupCreation($batch)
    {
        $this->backendService->groupsCreation($batch);
    }

    /**
     * Handle the Batch "deleted" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function deleted(Batch $batch)
    {
        //
    }

    /**
     * Handle the Batch "restored" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function restored(Batch $batch)
    {
        //
    }

    /**
     * Handle the Batch "force deleted" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function forceDeleted(Batch $batch)
    {
        //
    }
}
