<?php

namespace App\Mail;

use App\Models\Applicant;
use App\Models\Mvcamp;
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
    public $carers_unified;
    public $carers;

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
        $this->carers_unified = [];
        $this->carers = \App\Models\ApplicantsGroup::find($this->applicant->group_id)->carers();

        if ($this->campFullData->table == 'mcamp') {
            $vbatchId = $this->applicant->batch->vbatch?->id;
            if ($vbatchId) {
                $this->carers_unified = \App\Models\Applicant::where('batch_id', $vbatchId)
                // 1. 預加載：把 mvcamp 資料一次抓進記憶體，避免 N+1
                ->with('mvcamp')
                // 2. 篩選：只抓出符合特定自我介紹內容的 Applicant
                ->whereRelation('mvcamp', 'self_intro', \App\Models\Mvcamp::DESCRIPTION_UNIFIED_CONTACT)
                ->get();
            }
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
