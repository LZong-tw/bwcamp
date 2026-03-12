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
    protected $applicantId;
    protected $camp_info;
    protected $isGetSN;
    protected $campOrVariant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicantId, $campInfo, $isGetSN = null)
    {
        $this->applicantId = $applicantId;
        $this->applicant = \App\Models\Applicant::with($campInfo->table)->find($applicantId);
        //上層查好了($campInfo)直接傳進來，不用再查一次
        $this->camp_info = $campInfo;   //camp 合併 batch 欄位
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

        if (!$this->applicant) {
            \Log::error("SendApplicantMail: applicant {$this->applicantId} not found.");
            return;
        } elseif ($this->applicant->deleted_at) {
            \Log::error("SendApplicantMail: applicant {$this->applicantId} cancelled registration");
            return;
        }
        $campTable = $this->camp_info->table;

        // 動態載入電子郵件設定
        $this->setEmail($campTable);

        \Mail::to($this->applicant->email)->send(new \App\Mail\QueuedApplicantMail($this->applicant, $this->camp_info, $this->isGetSN));

        if ($campTable == 'ceocamp' || $campTable == 'wcamp') {
            // 代填人/推薦人：必填
            if ($this->applicant->introducer_email) {
                \Mail::to($this->applicant->introducer_email)->send(new \App\Mail\IntroducerMail($this->applicant, $this->camp_info));
            }
            // 秘書：非必填
            $substitute_email = $this->applicant->$campTable?->substitute_email;
            if ($substitute_email) {
                \Mail::to($substitute_email)->send(new \App\Mail\SubstituteMail($this->applicant, $this->camp_info));
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
