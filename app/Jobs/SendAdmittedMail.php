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
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendAdmittedMail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailConfiguration;

    protected $applicant;

    protected $tries = 400;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicant_id)
    {
        //
        $this->applicant = \App\Models\Applicant::find($applicant_id);
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
        $applicant = $this->applicant;
        $applicant = $applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        // 動態載入電子郵件設定
        $this->setEmail($applicant->batch->camp->table, $applicant->batch->camp->variant);
        if(!isset($applicant->fee) || $applicant->fee == 0){
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp));
        }
        else{
            \PDF::setOption(['defaultFont' => '微軟正黑體']);
            $paymentFile = \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->download();
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp, $paymentFile));
        }

    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware() {
        if(!$this->applicant) {
            \Sentry\captureException(new \Exception('SendAdmittedMail: Applicant not found'));
            return [];
        }
        return [new WithoutOverlapping($this->applicant->batch->camp->id)];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->applicant->id;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(60);
    }
}
