<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EmailConfiguration;

class SendCheckInMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailConfiguration;

    protected $applicant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicant_id)
    {
        //
        $this->applicant = \App\Models\Applicant::find($applicant_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        sleep(10);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        $applicant = $this->applicant;
        $qr_code = \DNS2D::getBarcodePNG('{"applicant_id":' . $applicant->id . '}', 'QRCODE');
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->applicant->batch->camp->fullName . ' QR code 報到單<br>梯次：' . $applicant->batch->name . '<br>錄取序號：' . $applicant->group . $applicant->number . '<br>姓名：' . $applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
        $attachment =  $pdf->stream();
        // 動態載入電子郵件設定
        $this->setEmail($applicant->batch->camp->table);
        \Mail::to($applicant->email)->send(new \App\Mail\CheckInMail($applicant, $attachment));
    }
}
