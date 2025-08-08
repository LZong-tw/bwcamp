<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
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
        $this->scheduleAccountingChecks($schedule);
        $this->scheduleMaintenanceTasks($schedule);
        //$this->scheduleCampExports($schedule);
        
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
        $schedule->command('gen:BankSecondBarcode 96')->dailyAt("0:28");
        $schedule->command('import:Form 96')->dailyAt("0:29");
        $schedule->command('export:Applicant 96')->dailyAt("0:30");

        // 其他營隊匯出排程
        $schedule->command('export:Applicant 97')->dailyAt("0:45");  // ceovcamp
        $schedule->command('export:Applicant 102')->dailyAt("1:00");  // ecamp_c
        $schedule->command('export:Applicant 100')->dailyAt("1:30");  // ecamp_s
        $schedule->command('export:Applicant 106')->dailyAt("2:00");  // ecamp_n
        $schedule->command('export:Applicant 103')->dailyAt("2:30");  // evcamp_c
        $schedule->command('export:Applicant 101')->dailyAt("3:00");  // evcamp_s
        $schedule->command('export:Applicant 107')->dailyAt("3:30");  // evcamp_n
    }

    /**
     * 排程報到資料匯出任務
     * 規則：08:00-09:00 每分鐘執行; 09:00-12:00 每十分鐘執行
     */
    private function scheduleCheckInExports(Schedule $schedule)
    {
        $timeRanges = $this->getCheckInTimeRanges();

        // ceocamp (ID: 96) - 7/26-7/27
        /*$this->scheduleCheckInForCamp($schedule, 96, [
            'everyMinute' => [
                $timeRanges['day4']['peak'],
                $timeRanges['day5']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges['test1'],
                $timeRanges['day4']['normal'],
                $timeRanges['day5']['normal'],
            ]
        ]);

        // ecamp_c (ID: 102) - 7/25-7/26
        $this->scheduleCheckInForCamp($schedule, 102, [
            'everyMinute' => [
                $timeRanges['day3']['peak'],
                $timeRanges['day4']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges['test2'],
                $timeRanges['day3']['normal'],
                $timeRanges['day4']['normal'],
            ]
        ]);

        // ecamp_n (ID: 106) - 7/25-7/26
        $this->scheduleCheckInForCamp($schedule, 106, [
            'everyMinute' => [
                $timeRanges['day3']['peak'],
                $timeRanges['day4']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges['test2'],
                $timeRanges['day3']['normal'],
                $timeRanges['day4']['normal'],
            ]
        ]);

        // ecamp_s (ID: 100) - 7/18-7/19
        $this->scheduleCheckInForCamp($schedule, 100, [
            'everyMinute' => [
                $timeRanges['day1']['peak'],
                $timeRanges['day2']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges['test1'],
                $timeRanges['day1']['normal'],
                $timeRanges['day2']['normal'],
            ]
        ]);*/

        // ycamp (ID: 104) - 8/8-8/9
        $this->scheduleCheckInForCamp($schedule, 104, [
            'everyMinute' => [
                $timeRanges['test1'],
                $timeRanges['day1']['peak'],
                $timeRanges['day2']['peak'],
            ],
            'everyTenMinutes' => [
                $timeRanges['day1']['normal'],
                $timeRanges['day2']['normal'],
            ]
        ]);
    }

    /**
     * 取得報到時間範圍設定
     */
    private function getCheckInTimeRanges(): array
    {
        return [
            // 測試時段
            'test1' => ['start' => '2025-07-17 18:00:00', 'end' => '2025-07-17 21:00:00'],
            'test2' => ['start' => '2025-07-24 18:00:00', 'end' => '2025-07-24 21:00:00'],
            
            // 第一天 (8/8)
            'day1' => [
                'peak'   => ['start' => '2025-08-08 07:00:00', 'end' => '2025-08-08 15:00:00'],
                'normal' => ['start' => '2025-08-08 15:00:01', 'end' => '2025-08-08 20:59:59'],
            ],
            
            // 第二天 (8/9)
            'day2' => [
                'peak'   => ['start' => '2025-08-09 07:00:00', 'end' => '2025-08-09 15:00:00'],
                'normal' => ['start' => '2025-08-09 15:00:01', 'end' => '2025-08-09 20:59:59'],
            ],

            // 第三天 (7/25)
            /*'day3' => [
                'peak'   => ['start' => '2025-07-25 08:00:00', 'end' => '2025-07-25 09:00:00'],
                'normal' => ['start' => '2025-07-25 09:01:00', 'end' => '2025-07-25 11:59:59'],
            ],

            // 第四天 (7/26)
            'day4' => [
                'peak'   => ['start' => '2025-07-26 08:00:00', 'end' => '2025-07-26 09:00:00'],
                'normal' => ['start' => '2025-07-26 09:01:00', 'end' => '2025-07-26 11:59:59'],
            ],

            // 第五天 (7/27)
            'day5' => [
                'peak'   => ['start' => '2025-07-27 08:00:00', 'end' => '2025-07-27 09:00:00'],
                'normal' => ['start' => '2025-07-27 09:01:00', 'end' => '2025-07-27 11:59:59'],
            ],*/
        ];
    }

    /**
     * 為特定營隊設定報到匯出排程
     */
    private function scheduleCheckInForCamp(Schedule $schedule, int $campId, array $timeConfig)
    {
        $command = "export:CheckIn {$campId} --renew=1";

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
