<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $admittedNo, $campData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $admittedNo, $campData)
    {
        //
        $this->name = $name;
        $this->admittedNo = $admittedNo;
        $this->campData = $campData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->campData->abbreviation . '錄取通知')
                ->view($this->campData->table . ".admittedMail");
    }
}
