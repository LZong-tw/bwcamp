<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckInMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant, $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $attachment = null) {
        //
        $this->applicant = $applicant;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject($this->applicant->batch->camp->abbreviation . '報到通知')
                ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail")
                ->attachData($this->attachment, $this->applicant->batch->camp->abbreviation . $this->applicant->id . $this->applicant->name . 'QR code 報到單.pdf', [
                    'mime' => 'application/pdf',
                ]);
    }
}
