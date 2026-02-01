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
        $this->carers = [];

        if ($this->campFullData->table == 'mcamp') {
            $vbatch = $this->applicant->batch->vbatch ?? null;
            $vcamp = $this->applicant->camp->vcamp ?? null;
            $this->carers_unified =
                \App\Models\Applicant::where('batch_id', $vbatch->id)
                ->join('mvcamp','mvcamp.applicant_id', '=' , 'applicants.id')
                ->where('self_intro', '第5組義工窗口')
                ->get();
            $orgs = \App\Models\CampOrg::where('group_id', $this->applicant->group_id)->get();
            $carers = $orgs->pluck("users")->flatten();
            $carers = $carers->map(function ($carer) use ($vcamp) {
                $carer["mobile"] = $carer->application_log->whereIn('batch_id', $vcamp->batchs->pluck('id'))->first()?->mobile?? "";
                return $carer;
            });
            $this->carers = $carers;
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
