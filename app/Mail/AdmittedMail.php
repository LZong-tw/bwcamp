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

    public $applicant, $campFullData, $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant, $campFullData, $attachment = null) {
        //
        $this->applicant = $applicant;
        $this->campFullData = $campFullData;
        $this->attachment = $attachment;

        //大專教師營
        if ($this->applicant->batch->camp->table == 'utcamp') {
            $this->applicant->xgroup = $this->applicant->group;
            $this->applicant->xnumber = $this->applicant->number;
            if (strpos($this->applicant->group,'B') !== false) {
                $this->applicant->xsession = '桃園場';
                $this->applicant->xaddr = '桃園市中壢區成章四街120號';
            } elseif (strpos($this->applicant->group,'C') !== false) {
                $this->applicant->xsession = '新竹場';
                $this->applicant->xaddr = '新竹縣新豐鄉新興路1號';
            } elseif (strpos($this->applicant->group,'D') !== false) {
                $this->applicant->xsession = '台中場';
                $this->applicant->xaddr = '台中市西區民生路227號';
            } elseif (strpos($this->applicant->group,'E') !== false) {
                $this->applicant->xsession = '雲林場';
                $this->applicant->xaddr = '雲林縣斗六市慶生路6號';
            } elseif (strpos($this->applicant->group,'F') !== false) {
                $this->applicant->xsession = '台南場';
                $this->applicant->xaddr = '台南市東區大學路1號';
            } elseif (strpos($this->applicant->group,'G') !== false) {
                $this->applicant->xsession = '高雄場';
                $this->applicant->xaddr = '高雄市新興區中正四路53號12樓之7';
            } else {
                $this->applicant->xsession = '台北場';
                $this->applicant->xaddr = '台北市南京東路四段165號九樓 福智學堂';
            }
        }
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
        if(!$this->attachment){
            return $this->subject($this->campFullData->abbreviation . '錄取通知')
                ->view('camps.' . $this->campFullData->table . ".admittedMail");
        }
        else{
            return $this->subject($this->campFullData->abbreviation . '錄取通知')
                ->view('camps.' . $this->campFullData->table . ".admittedMail")
                ->attachData($this->attachment, '繳費暨錄取通知單' . \Carbon\Carbon::now()->format('YmdHis') . $this->campFullData->table . $this->applicant->group . $this->applicant->number . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
        }
    }
}
