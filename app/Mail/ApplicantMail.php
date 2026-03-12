<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class ApplicantMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $applicant;
    public $camp_info;
    public $isGetSN;

    public $camp_data, $campData;   //for compatibility

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $campInfo, $isGetSN = false) {
        //上層查好了($applicant, $campInfo)直接傳進來，不用再查一次
        $this->applicant = $applicant;
        $this->camp_info = $campInfo;   //camp 合併 batch 欄位
        $this->isGetSN = $isGetSN;
        $this->camp_data = $campInfo;
        $this->campData = $campInfo;
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

        $campTable = $this->camp_info->table;

        if (!$this->isGetSN) {
            if ($campTable == 'ceocamp' || $campTable == 'wcamp') {
                return $this->subject($this->camp_info->abbreviation . '推薦報名完成')
                    ->view('camps.' . $campTable . ".applicantMail");
            } else {
                return $this->subject($this->camp_info->abbreviation . '報名完成')
                    ->view('camps.' . $campTable . ".applicantMail");
            }
        } else {
            $viewPathCamp = 'camps.' . $campTable . '.SNMail';
            $viewPathGeneral = 'components.general.SNMail';
            $viewPath = View::exists($viewPathCamp)? $viewPathCamp: $viewPathGeneral;
            return $this->subject($this->camp_info->abbreviation . '序號查詢')
                    ->view($viewPath);
        }
    }
}
