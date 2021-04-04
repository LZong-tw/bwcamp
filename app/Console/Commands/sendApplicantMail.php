<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Camp;
use App\Models\Applicant;
use App\Mail\ApplicantMail;
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
    public function handle() {
        // 動態載入電子郵件設定
        $this->setEmail($this->argument('camp'));
        switch($this->argument('mailType')){
            case "applicantMail":
                if(is_numeric($this->argument('camp'))){
                    $camp = Camp::find($this->argument('camp'));
                }
                else{
                    $camp = Camp::where('table', $this->argument('camp'))->orderBy('id', 'desc')->first();
                }
                $applicant = Applicant::find($this->argument('applicant_id'));
                if($applicant->batch->camp->id == $camp->id){
                    Mail::to($applicant)->send(new ApplicantMail($applicant, $camp));
                    $this->info("成功寄送報名成功郵件。");
                }
                else{
                    $this->error("收件者營隊與指定營隊不一致。");
                }
                break;
        }
        return 0;
    }
}
