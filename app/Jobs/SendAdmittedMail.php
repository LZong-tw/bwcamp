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
use App\Models\Applicant;

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
    public function __construct($applicantId, $campTable)
    {
        //eager load lodging and traffic, which is might be needed in the email view
        if ($campTable == null) {
            $this->applicant = Applicant::with(['lodging', 'traffic'])->findOrFail($applicantId);
        } else {
            $this->applicant = Applicant::with([$campTable, 'lodging', 'traffic'])->findOrFail($applicantId);
        }
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
        $applicant->admitted_at = \Carbon\Carbon::now();    //MCH
        $applicant->save();
        // 動態載入電子郵件設定
        $this->setEmail($applicant->batch->camp->table, $applicant->batch->camp->variant);
        if(!isset($applicant->fee) || $applicant->fee == 0 || $applicant->batch->camp->table == 'utcamp') {
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp));
        }
        else {
            $paymentFile = \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->setPaper('a3')->output();
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp, $paymentFile));
        }
        \logger('SendAdmittedMail, Applicant: ' . $applicant->id . ' Email: ' . $applicant->email . '   success');
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
