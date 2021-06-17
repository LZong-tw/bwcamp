<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ApplicantService;
use App\Traits\EmailConfiguration;

class SendAdmittedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailConfiguration;

    protected $applicant;

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
        sleep(10);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        $applicant = $this->applicant;
        $applicant = $applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        // 動態載入電子郵件設定
        $this->setEmail($applicant->batch->camp->table);
        if($applicant->fee == 0){
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp));
        }
        else{
            $paymentFile = \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->download();
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp, $paymentFile));
        }
        
    }
}
