<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Batch;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每日固定排程任務
        //$this->scheduleAccountingChecks($schedule);
        $this->scheduleMaintenanceTasks($schedule);
        $this->scheduleCampExports($schedule);

        // 動態報到匯出排程
        $this->scheduleCheckInExports($schedule);
    }

    /**
     * 排程會計檢查任務
     */
    private function scheduleAccountingChecks(Schedule $schedule)
    {
        $schedule->command('check:Accounting ycamp')->dailyAt("16:30");
    }

    /**
     * 排程系統維護任務
     */
    private function scheduleMaintenanceTasks(Schedule $schedule)
    {
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();
    }

    /**
     * 排程營隊資料匯出任務
     */
    private function scheduleCampExports(Schedule $schedule)
    {
        // CEO Camp 相關排程
        // $schedule->command('gen:BankSecondBarcode 96')->dailyAt("0:28");
        // $schedule->command('import:Form 96')->dailyAt("0:29");
        // $schedule->command('export:Applicant 96')->dailyAt("0:30");

        // 其他營隊匯出排程
        // $schedule->command('export:Applicant 97')->dailyAt("0:45");  // ceovcamp
        // $schedule->command('export:Applicant 102')->dailyAt("1:00");  // ecamp_c
        // $schedule->command('export:Applicant 100')->dailyAt("1:30");  // ecamp_s
        // $schedule->command('export:Applicant 106')->dailyAt("2:00");  // ecamp_n
        // $schedule->command('export:Applicant 103')->dailyAt("2:30");  // evcamp_c
        // $schedule->command('export:Applicant 101')->dailyAt("3:00");  // evcamp_s
        // $schedule->command('export:Applicant 107')->dailyAt("3:30");  // evcamp_n
        $schedule->command('export:Applicant 110')->dailyAt("0:10");  // nycamp
        $schedule->command('export:Applicant 108')->dailyAt("0:30");  // tcamp
        $schedule->command('export:Applicant 112')->dailyAt("1:00");  // utcamp
    }

    /**
     * 排程報到資料匯出任務
     * 規則：08:00-09:00 每分鐘執行; 09:00-12:00 每十分鐘執行
     */
    private function scheduleCheckInExports(Schedule $schedule)
    {
        //to do: create an interface for user instead of hard-code
        //tcamp
        $camp_id1 = 108;
        $batch_id1 = 217;

        $timeRanges1 = $this->getCheckInTimeRanges($batch_id1);

        $this->scheduleCheckInForCamp($schedule, $camp_id1, [
            'everyMinute' => [
                $timeRanges1['test1'],
                $timeRanges1['day1']['peak'],
                $timeRanges1['day2']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges1['day1']['normal'],
                $timeRanges1['day2']['normal'],
            ]
        ]);

        //utcamp
        $camp_id2 = 112;
        $batch_id2 = 221;

        $timeRanges2 = $this->getCheckInTimeRanges($batch_id2);

        $this->scheduleCheckInForCamp($schedule, $camp_id2, [
            'everyMinute' => [
                $timeRanges2['test1'],
                $timeRanges2['day1']['peak'],
                $timeRanges2['day2']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges2['day1']['normal'],
                $timeRanges2['day2']['normal'],
            ]
        ]);

    }

    /**
     * 取得報到時間範圍設定
     */
    private function getCheckInTimeRanges($batch_id): array
    {
        $batch = Batch::find($batch_id);
        $str_day1 = $batch->batch_start->format('Y-m-d');
        $str_prevDay = Carbon::parse($str_day1)->subDay()->format('Y-m-d');
        $str_nextDay = Carbon::parse($str_day1)->addDay()->format('Y-m-d');

        $peak_start = ' 07:00:00';
        $peak_end = ' 15:00:00';
        $normal_start = ' 15:00:01';
        $normal_end = ' 20:59:59';

        return [
            // 測試時段
            'test1' => [
                'peak'   => ['start' => $str_prevDay.$peak_start,   'end' => $str_prevDay.$peak_end],
                'normal' => ['start' => $str_prevDay.$normal_start, 'end' => $str_prevDay.$normal_end],
            ],

            // 第一天
            'day1' => [
                'peak'   => ['start' => $str_day1.$peak_start,   'end' => $str_day1.$peak_end],
                'normal' => ['start' => $str_day1.$normal_start, 'end' => $str_day1.$normal_end],
            ],

            // 第二天
            'day2' => [
                'peak'   => ['start' => $str_nextDay.$peak_start,   'end' => $str_nextDay.$peak_end],
                'normal' => ['start' => $str_nextDay.$normal_start, 'end' => $str_nextDay.$normal_end],
            ],
        ];
    }

    /**
     * 為特定營隊設定報到匯出排程
     */
    private function scheduleCheckInForCamp(Schedule $schedule, int $campId, array $timeConfig)
    {
        //$command = "export:CheckIn checkIn {$campId} --renew=1";
        $command = "export:CheckIn signIn {$campId} --renew=1";

        // 設定每分鐘執行的時段
        if (isset($timeConfig['everyMinute'])) {
            $schedule->command($command)
                ->everyMinute()
                ->when(function () use ($timeConfig) {
                    return $this->isInTimeRanges($timeConfig['everyMinute']);
                });
        }

        // 設定每十分鐘執行的時段
        if (isset($timeConfig['everyTenMinutes'])) {
            $schedule->command($command)
                ->everyTenMinutes()
                ->when(function () use ($timeConfig) {
                    return $this->isInTimeRanges($timeConfig['everyTenMinutes']);
                });
        }
    }

    /**
     * 檢查當前時間是否在指定的時間範圍內
     */
    private function isInTimeRanges(array $ranges): bool
    {
        $now = Carbon::now();

        foreach ($ranges as $range) {
            $start = Carbon::parse($range['start']);
            $end = Carbon::parse($range['end']);

            if ($now->between($start, $end)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
