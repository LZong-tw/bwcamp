<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EmailConfiguration;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SendCheckInMail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use EmailConfiguration;

    protected $applicant;
    protected $applicantId;
    protected $org = null;
    protected $tries = 400;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicantId, $orgId = null)
    {
        $this->applicantId = $applicantId;
        $this->applicant = \App\Models\Applicant::with('batch.camp')->find($applicantId);
        if ($orgId) {
            $this->org = \App\Models\CampOrg::find($orgId); //for vcamp
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        sleep(3);
        ini_set('memory_limit', -1);

        if (!$this->applicant) {
            \Log::error("SendCheckInMail, applicant {$this->applicantId} not found.");
            return;
        }
        $applicant = $this->applicant;
        $camp = $this->applicant->batch->camp;
        $campTable = $this->applicant->batch->camp->table;

        if ($campTable == 'coupon') {
            $qr_code = \DNS2D::getBarcodePNG('{"coupon_code":"' . $this->applicant->name . '"}', 'QRCODE');
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($camp->abbreviation . '<br>流水號：' . $this->applicant->group . $this->applicant->number . '<br>優惠碼：' . $this->applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
        } elseif ($campTable != '' && $campTable != 'ceovcamp' && $campTable != 'evcamp') {
            $qr_code = \DNS2D::getBarcodePNG('{"applicant_id":' . $this->applicant->id . '}', 'QRCODE');
            $pdf = \App::make('dompdf.wrapper');
            if ($this->applicant->number) {
                $pdf->loadHTML($camp->fullName . ' QR code 報到單<br>梯次：' . $this->applicant->batch->name . '<br>錄取序號：' . $this->applicant->group . $this->applicant->number . '<br>姓名：' . $this->applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
            } elseif ($campTable == "ecamp" && $this->applicant->batch->name == "雲嘉") {
                if ($this->applicant->group == "第一組") {
                    $pdf->loadHTML($camp->fullName . ' QR code 報到單<br>梯次：' . $this->applicant->batch->name . '<br>組別：第十一組<br>姓名：' . $this->applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
                } else {
                    $pdf->loadHTML($camp->fullName . ' QR code 報到單<br>梯次：' . $this->applicant->batch->name . '<br>組別：第十二組<br>姓名：' . $this->applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
                }
            } else {
                $pdf->loadHTML($camp->fullName . ' QR code 報到單<br>梯次：' . $this->applicant->batch->name . '<br>組別：' . $this->applicant->group . '<br>姓名：' . $this->applicant->name . '<br><img src="data:image/png;base64,' . $qr_code . '" alt="barcode" height="200px"/>')->setPaper('a6');
            }
        }
        $attachment = isset($pdf) ? $pdf->output() : null;
        // 動態載入電子郵件設定
        $this->setEmail($campTable, $camp->variant);
        \Mail::to($this->applicant->email)->send(new \App\Mail\CheckInMail($this->applicant, $this->org, $attachment));
        \logger('SendCheckInMail ' . $this->applicantId . ' success');
    }


    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->applicantId;
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
