<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class QueuedApplicantMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $applicant;
    public $camp_info;
    public $camp_table;
    public $isGetSN;

    public $camp_data;
    public $campData;   //for compatibility

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $campInfo, $isGetSN = false)
    {
        //上層查好了($applicant, $campInfo)直接傳進來，不用再查一次
        $this->applicant = $applicant;
        $this->camp_info = $campInfo;   //camp 合併 batch 欄位
        $this->camp_table = $campInfo->table;
        $this->isGetSN = $isGetSN;
        $this->camp_data = $campInfo;
        $this->campData = $campInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('time', time());
        });

        if (!$this->isGetSN) {
            if ($this->camp_table == 'ceocamp' || $this->camp_table == 'wcamp') {
                return $this->subject($this->camp_info->abbreviation . '推薦報名完成')
                    ->view('camps.' . $this->camp_table . ".applicantMail");
            } else {
                return $this->subject($this->camp_info->abbreviation . '報名完成')
                    ->view('camps.' . $this->camp_table . ".applicantMail");
            }
        } else {
            $viewPathCamp = 'camps.' . $this->camp_table . '.SNMail';
            $viewPathGeneral = 'components.general.SNMail';
            $viewPath = View::exists($viewPathCamp) ? $viewPathCamp : $viewPathGeneral;
            return $this->subject($this->camp_info->abbreviation . '序號查詢')
                    ->view($viewPath);
        }
    }
}
