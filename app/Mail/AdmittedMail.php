<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmittedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $applicant;
    public $campFullData;
    public $attachment;
    public $etc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $campFullData, $attachment = null)
    {
        //
        $this->applicant = $applicant;
        $this->campFullData = $campFullData;
        $this->attachment = $attachment;
        $this->etc = $this->applicant->user?->roles?->where("camp_id", \App\Models\Vcamp::find($this->applicant->camp->id)->mainCamp->id)->first()?->section;
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

        if ($this->campFullData->table == 'ceocamp' || $this->campFullData->table == 'ecamp') {
            return $this->subject($this->campFullData->abbreviation . '錄取通知')
                ->view('camps.' . $this->campFullData->table . ".admittedMail");
        }
        if (!$this->attachment) {
            return $this->subject($this->campFullData->abbreviation . '錄取通知')
                ->view('camps.' . $this->campFullData->table . ".admittedMail");
        } else {
            return $this->subject($this->campFullData->abbreviation . '錄取通知')
                ->view('camps.' . $this->campFullData->table . ".admittedMail")
                ->attachData($this->attachment, '繳費暨錄取通知單' . \Carbon\Carbon::now()->format('YmdHis') . $this->campFullData->table . $this->applicant->group . $this->applicant->number . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
        }
    }
}
