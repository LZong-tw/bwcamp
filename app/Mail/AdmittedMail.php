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

        //找尋小組長及他的電話
        //找到group
        // ->找到相關camp_orgs
        // ->找到被assign此職務的 carers(users)
        // ->找到applicant(vcamp_id)
        // ->mobile
        $this->group = $applicant->group;
        $this->camp_orgs = $group->camp_orgs->sortby('order');
        $this->group_carers = collect();    //empty
        foreach($this->camp_orgs as $org) {
            $this->group_carers->push($org->users);
        }
        $this->carers = $this->group_carers;
        foreach($this->group_carers as $carer) {
            $carer->mobile = $carer->applicant($campFullData->camp_id)->mobile;
        }
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
