<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Camp;
use App\Models\Applicant;
use App\Mail\AdmittedMail;
use App\Mail\ApplicantMail;
use App\Mail\CheckInMail;
use App\Mail\NotAdmittedMail;
use App\Mail\IntroducerMail;
use App\Mail\SubstituteMail;
use App\Traits\EmailConfiguration;

class sendApplicantMail extends Command
{
    use EmailConfiguration;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail {mailType} {camp} {applicant_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '寄送電子郵件';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 動態載入電子郵件設定
        $this->setEmail($this->argument('camp'));
        if (is_numeric($this->argument('camp'))) {
            $camp = Camp::find($this->argument('camp'));
        } else {
            $camp = Camp::where('table', $this->argument('camp'))->orderBy('id', 'desc')->first();
        }
        if (!$camp) {
            $this->error("找不到 {$this->argument('camp')} 營隊資訊。");
            return;
        }

        $applicant = Applicant::with('batch')->find($this->argument('applicant_id'));
        if (!$applicant) {
            $this->error("找不到收件者 {$this->argument('applicant_id')}。");
            return;
        }
        $batch = $applicant ? $applicant->batch : null;

        //感覺輸入時可以不要 {camp} 資訊，而是直接從applicant去讀取，就不用做這樣額外檢查？
        if ($applicant->batch->camp->id != $camp->id) {
            $this->error("收件者營隊與指定營隊不一致。");
            return;
        } else {
            //補上Batch的資訊，和CampController裡面的camp_info一樣，因為Email裡面會用到Batch的資訊
            $camp->forceFill($batch->attributesToArray());
        }
        switch ($this->argument('mailType')) {
            case "applicantMail":
                Mail::to($applicant)->send(new ApplicantMail($applicant, $camp));
                $this->info("成功寄送報名成功郵件。");
                break;
            case "admittedMail":
                Mail::to($applicant)->send(new AdmittedMail($applicant, $camp));
                $this->info("成功寄送錄取郵件。");
                break;
            case "checkInMail":
                Mail::to($applicant)->send(new CheckInMail($applicant));
                $this->info("成功寄送報到郵件。");
                break;
            case "notAdmittedMail":
                Mail::to($applicant)->send(new NotAdmittedMail($applicant));
                $this->info("成功寄送不錄取郵件。");
                break;
            case "introducerMail":
                Mail::to($applicant)->send(new IntroducerMail($applicant, $camp));
                $this->info("成功寄送推薦人郵件。");
                break;
        }
        return 0;
    }
}
