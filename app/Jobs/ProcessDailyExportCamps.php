<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use App\Models\Camp;
use Carbon\Carbon;

class ProcessDailyExportCamps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 1. 使用 Cache 存儲當天的營隊資料，避免每分鐘都進 DB
        // 我們讓 Cache 在每天凌晨自動更新，或設定 24 小時過期
        $thisYearCamps = Cache::remember('daily_camp_data', now()->addHours(25), function () {
            return Camp::with('batches')
                ->where('year', now()->year)
                ->get()
                ->sortBy('table'); // 根據你的需求排序
        });

        // 2. 遍歷分類後的營隊
        $table_prev = "";
        $add30 = 0;
        foreach ($thisYearCamps as $camp) {
            
            // 找出該類別中最前面的日期 (利用前面定義的 Accessor 或直接 min)
            $exportStartDate = $camp->registration_start; // 假設這是你用來判斷是否開始匯出的日期欄位
            $exportEndDate = $camp->batch_end_latest; // 假設這是你用來判斷是否結束匯出的日期欄位

            // 3. 根據該類別的最早日期，決定是否要執行排程
            // 範例：如果今天已經超過最早日期，才開始執行某個匯出指令
            if ($exportStartDate && now()->startOfDay()->gte(Carbon::parse($exportStartDate)) && now()->startOfDay()->lte(Carbon::parse($exportEndDate))) {

                $times = $this->getCustomTimeForCamp($camp->table);
                if ($camp->table === $table_prev) {
                    $add30 += 30;
                    $times = array_map(function($time) use ($add30) {
                        return Carbon::parse($time)->addSeconds($add30)->format('H:i');
                    }, $times);
                } else {
                    $add30 = 0; // reset for new camp
                }
                $table_prev = $camp->table;

                //one may export data multiple times a day
                foreach ($times as $time) {
                    if ($camp->table === 'ceocamp') {
                        $timeSubOneMIn = Carbon::parse($time)->subMinute()->format('H:i');
                        $timeSubTwoMin = Carbon::parse($time)->subMinute(2)->format('H:i');
                        $schedule->command('gen:BankSecondBarcode 96')->dailyAt(timeSubTwoMIn);
                        // $schedule->command('import:Form 96')->dailyAt(timeSubTwoMin);
                    }
                    $schedule->command("export:Applicant {$camp->id}")
                        ->dailyAt($time);
                }
            }
        }
    }

    /**
     * 輔助方法：根據 ID 對應你原本硬編碼的時間 (或是存在 DB 裡)
     */
    private function getCustomTimeForCamp($campTable)
    {
        $times = [
            'ceocamp'  => ["00:30"],
            'ceovcamp' => ["00:45"],
            'ecamp'    => ["1:00"], //1:30, 2:00
            'evcamp'   => ["2:30"], //3:00, 3:30
            `nycamp`   => ["4:00"],
            'tcamp'    => ["4:30"],
            'utcamp'   => ["5:00", "11:30", "16:35"],
            // ... 其他對應
        ];
        return $times[$campTable] ?? [];
    }
}
