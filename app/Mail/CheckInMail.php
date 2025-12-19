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
    use Queueable;
    use SerializesModels;

    public $applicant;
    public $org;
    public $attachment;
    public $etc;
    public $content_link_chn;
    public $content_link_eng;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $org = null, $attachment = null)
    {
        //
        $this->applicant = $applicant;
        $this->org = $org;
        $this->attachment = $attachment;
        $this->etc = $this->applicant->user?->roles?->where("camp_id", \App\Models\Vcamp::find($this->applicant->camp->id)->mainCamp->id)->first()?->section;
        $this->content_link_chn = $this->applicant->camp->dynamic_stats?->where('purpose', 'checkInMail_chn')?->first()?->google_sheet_url ?? [];
        $this->content_link_eng = $this->applicant->camp->dynamic_stats?->where('purpose', 'checkInMail_eng')?->first()?->google_sheet_url ?? [];
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

        $subject1 = '報到通知';
        if ($this->applicant->batch->camp->fullName == "心之呼吸｜2025大專教師生命成長營") {
            $subject1 = '行前通知單';
        } elseif ($this->applicant->batch->camp->table == "nycamp") {
            if ($this->applicant->language == "Mandarin")
                $subject1 = ' 【行前通知】';
            else 
                $subject1 = ' Pre-Departure Guide';
        }

        if ($this->applicant->batch->camp->table == 'coupon') {
            return $this->subject($this->applicant->batch->camp->fullName)
                    ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail", ['applicant' => $this->applicant]);
            //                    ->attachData($this->attachment, $this->applicant->batch->camp->abbreviation . '.pdf', [
            //                        'mime' => 'application/pdf',
            //                    ]);
        } elseif (!$this->attachment) {
            return $this->subject($this->applicant->batch->camp->abbreviation . $subject1)
                    ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail", ['applicant' => $this->applicant]);
        } else {
            return $this->subject($this->applicant->batch->camp->abbreviation . $subject1)
                    ->view('camps.' . $this->applicant->batch->camp->table . ".checkInMail", ['applicant' => $this->applicant])
                    ->attachData($this->attachment, $this->applicant->batch->camp->abbreviation . $this->applicant->id . $this->applicant->name . 'QR code 報到單.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        }
    }

    public function attachments()
    {
        if ($this->applicant->batch->camp->abbreviation == "2023教師營") {
            return [
                Attachment::fromPath(public_path('downloads/tcamp2023/schedule.pdf')),
            ];
        }
    }
}
