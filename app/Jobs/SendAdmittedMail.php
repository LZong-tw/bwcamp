<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdmittedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle()
    {
        //
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        $applicant = $this->applicant;
        $paymentFile = \PDF::loadView('backend.registration.paymentFormPDF', compact('applicant'))->download();
        \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp, $paymentFile));
    }
}
