<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IntroducerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant, $applicant_id, $camp, $campData, $isGetSN;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant_id, $campOrVariant, $isGetSN = false) {
        //
        $this->applicant_id = $applicant_id;
        $this->camp = $campOrVariant;
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
        $this->applicant = Applicant::find($this->applicant_id);
        $this->campData = $this->applicant->batch->camp;
        if(!$this->isGetSN){
            return $this->subject($this->applicant->batch->camp->abbreviation . '推薦報名完成')
                    ->to($this->applicant->introducer_email)
                    ->view('camps.' . $this->applicant->batch->camp->table . ".introducerMail");
        }
        else{
            return $this->subject($this->applicant->batch->camp->abbreviation . '序號查詢')
                    ->to($this->applicant->introducer_email)
                    ->view('camps.' . $this->applicant->batch->camp->table . ".SNMail");
        }
    }
}
