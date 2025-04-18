<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant, $campData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $campData, $isGetSN = false) {
        //
        $this->applicant = $applicant;
        $this->campData = $campData;
        $this->isGetSN = $isGetSN;
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
        if(!$this->isGetSN){
            return $this->subject($this->campData->abbreviation . '報名完成')
                    ->view('camps.' . $this->campData->table . ".applicantMail");
        }
        else{
            return $this->subject($this->campData->abbreviation . '序號查詢')
                    ->view('camps.' . $this->campData->table . ".SNMail");
        }
    }
}
