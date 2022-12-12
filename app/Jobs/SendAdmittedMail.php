<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ApplicantService;
use App\Traits\EmailConfiguration;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendAdmittedMail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailConfiguration;

    protected $applicant;

    protected $tries = 512;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicant_id)
    {
        //
        $this->applicant = \App\Models\Applicant::find($applicant_id);
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
            //dd($this->applicant);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApplicantService $applicantService)
    {
        //
        sleep(10);
        ini_set('memory_limit', -1);
        $applicant = $this->applicant;
        $applicant = $applicantService->checkIfPaidEarlyBird($applicant);
        $applicant->save();
        // 動態載入電子郵件設定
        $this->setEmail($applicant->batch->camp->table, $applicant->batch->camp->variant);
        if(!isset($applicant->fee) || $applicant->fee == 0){
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp));
        }
        else{
            $paymentFile = \PDF::loadView('camps.' . $applicant->batch->camp->table . '.paymentFormPDF', compact('applicant'))->download();
            \Mail::to($applicant->email)->send(new \App\Mail\AdmittedMail($applicant, $applicant->batch->camp, $paymentFile));
        }

    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware() {
        return [new WithoutOverlapping($this->applicant->batch->camp->id)];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->applicant->id;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(60);
    }
}
