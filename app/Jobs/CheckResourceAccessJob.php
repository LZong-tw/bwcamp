<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckResourceAccessJob implements ShouldQueue
{
    use Batchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected $applicant,
        protected $user,
        protected $campFullData
    ) {
    }

    public function handle()
    {
        $cacheKey = "user_{$this->user->id}_can_access_{$this->applicant->id}";
        return cache()->remember($cacheKey, now()->addMinutes(10), function () {
            return $this->user->canAccessResource($this->applicant, 'read', $this->campFullData, target: $this->applicant);
        });
    }
}
