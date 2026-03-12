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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use EmailConfiguration;

    protected $applicant;
    protected $applicantId;
    protected $tries = 400;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicantId, $campTable = null)
    {
        $this->applicantId = $applicantId;
        //eager load lodging and traffic, which might be needed in the email view
        $relations = ['batch.camp', 'lodging', 'traffic'];
        if ($campTable) {
            $relations[] = $campTable;
        }
        $this->applicant = Applicant::with($relations)->find($applicantId);
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

        if (!$this->applicant) {
            \Log::error("SendAdmittedMail: applicant {$this->applicantId} not found.");    
            return;
        }

        $applicant = $this->applicant;
        $applicant = $applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->admitted_at = \Carbon\Carbon::now();    //MCH
        $applicant->save();
        $camp = $this->applicant->batch->camp;

        // 動態載入電子郵件設定
        $this->setEmail($camp->table, $camp->variant);
        if (!isset($applicant->fee) || $applicant->fee == 0 || $camp->table == 'utcamp') {
            //無費用，或有費用但不需繳費單
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $camp));
        } else {
            //需繳費單
            $paymentFile = \PDF::loadView('camps.' . $camp->table . '.paymentFormPDF', compact('applicant'))->setPaper('a3')->output();
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $camp, $paymentFile));
        }
        \logger('SendAdmittedMail: applicant ' . $this->applicantId . ' Email: ' . $applicant->email . ' success');
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        if (!$this->applicant) {
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
        return $this->applicantId;
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
