<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EmailConfiguration;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SendApplicantMail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use EmailConfiguration;

    protected $applicant;
    protected $isGetSN;
    protected $campOrVariant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicant_id, $isGetSN = null)
    {
        //
        $this->applicant = \App\Models\Applicant::find($applicant_id);
        $this->isGetSN = $isGetSN;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        sleep(3);
        ini_set('memory_limit', -1);
        $applicant = $this->applicant;
        if (!$applicant || $applicant->deleted_at) {
            return;
        }
        $camp = $applicant->batch->camp;
        $this->campOrVariant = $camp->variant ? $camp->variant : $camp->table;
        // 動態載入電子郵件設定
        $this->setEmail($this->campOrVariant);
        \Mail::to($applicant->email)->send(new \App\Mail\QueuedApplicantMail($applicant->id, $this->campOrVariant, $this->isGetSN));
        if ($this->campOrVariant == 'ceocamp') {
            // 代填人 / 推薦人
            \Mail::to($applicant->introducer_email)->send(new \App\Mail\IntroducerMail($applicant->id, $this->campOrVariant, $this->isGetSN));
            $model = '\\App\\Models\\' . ucfirst($camp->table);
            $camp_related_data = $model::where('applicant_id', $applicant->id)->first();
            if ($camp_related_data->substitute_email) {
                // 秘書
                \Mail::to($camp_related_data->substitute_email)->send(new \App\Mail\SubstituteMail($applicant->id, $this->campOrVariant, $this->isGetSN));
            }
        }
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
