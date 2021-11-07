<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ApplicantService;
use App\Traits\EmailConfiguration;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SendAdmittedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailConfiguration;

    protected $applicant, $isGetSN;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicant_id, $isGetSN = null)
    {
        //
        $this->applicant = \App\Models\Applicant::find($applicant_id);
        $this->isGetSN = $isGetSN
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApplicantService $applicantService)
    {
        //
        sleep(3);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        $applicant = $this->applicant;
        $camp = $applicant->batch->camp;
        // 動態載入電子郵件設定
        $this->setEmail($camp->table, $camp->variant);
        \Mail::to($applicant->email)->send(new \App\Mail\QueuedApplicantMail($applicant->id, $camp->variant ? $camp->variant : $camp->table, $this->isGetSN));        
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware() {
        return [new WithoutOverlapping($this->user->id)];
    }
}
