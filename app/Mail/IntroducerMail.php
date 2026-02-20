<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IntroducerMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $applicant;
    public $camp_info;
    public $camp_table;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $campInfo) {
        //上層查好了($applicant, $campInfo)直接傳進來，不用再查一次
        $this->applicant = $applicant;
        $this->camp_info = $campInfo;   //camp 合併 batch 欄位
        $this->camp_table = $campInfo->table;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $this->withSwiftMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('time', time());
        });
        return $this->subject($this->camp_info->abbreviation . '推薦報名完成')
            ->to($this->applicant->introducer_email)
            ->view('camps.' . $this->camp_table . ".introducerMail");
    }
}
