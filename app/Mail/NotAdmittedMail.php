<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotAdmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant) {
        //
        $this->applicant = $applicant;
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
        if($this->applicant->camp->table == 'ecamp'){
            return $this->subject($this->applicant->camp->abbreviation . '感謝函')
                ->view('camps.' . $this->applicant->camp->table . ".notAdmittedMail");
        }
        return $this->subject($this->applicant->batch->camp->abbreviation . '通知信')
                ->view('camps.' . $this->applicant->batch->camp->table . ".notAdmittedMail");
    }
}
