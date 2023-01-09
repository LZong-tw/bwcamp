<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

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
        $this->withSwiftMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('time', time());
        });
        if($this->applicant->batch->camp->table == 'coupon'){
            return $this->subject($this->applicant->batch->camp->fullName)
                    ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail")
                    ->attachData($this->attachment, $this->applicant->batch->camp->abbreviation . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        }
        elseif(!$this->attachment){
            return $this->subject($this->applicant->batch->camp->abbreviation . '報到通知')
                    ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail");            
        }
        else{
            return $this->subject($this->applicant->batch->camp->abbreviation . '報到通知')
                    ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail")
                    ->attachData($this->attachment, $this->applicant->batch->camp->abbreviation . $this->applicant->id . $this->applicant->name . 'QR code 報到單.pdf', [
                        'mime' => 'application/pdf',
                    ]);            
        }
    }

    public function attachments()
    {
        if ($this->applicant->batch->camp->abbreviation == "2023教師營") {
            return [
                Attachment::fromPath(public_path('downloads\\tcamp2023\\schedule.pdf')),
            ];
        } 
    }
}
