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
        $schedule->command('check:Accounting ycamp')->dailyAt("16:30");
        //$schedule->command('check:Accounting ceocamp')->dailyAt("16:30");
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();

        $schedule->command('gen:BankSecondBarcode 96')->dailyAt("0:28"); //ceocamp
        $schedule->command('import:Form 96')->dailyAt("0:29"); //ceocamp
        $schedule->command('export:Applicant 96')->dailyAt("0:30");     //ceocamp,2nd
        $schedule->command('export:Applicant 97')->dailyAt("0:45");     //ceovcamp
        $schedule->command('export:Applicant 102')->dailyAt("1:00");    //ecamp_c
        //$schedule->command('export:Applicant 100')->dailyAt("1:30");  //ecamp_s
        $schedule->command('export:Applicant 106')->dailyAt("2:00");    //ecamp_n
        $schedule->command('export:Applicant 103')->dailyAt("2:30");    //evcamp_c
        //$schedule->command('export:Applicant 101')->dailyAt("3:00");  //evcamp_s
        $schedule->command('export:Applicant 107')->dailyAt("3:30");    //evcamp_n

        //08:00-09:00 每一分鐘一次; 09:00-12:00 每十分鐘一次
        $day1_start1    = Carbon::parse('2025-07-24 13:00:00');
        $day1_end1      = Carbon::parse('2025-07-24 21:00:00');
        $day2_start1    = Carbon::parse('2025-07-25 08:00:00');
        $day2_end1      = Carbon::parse('2025-07-25 11:59:59');
        $day3_start1    = Carbon::parse('2025-07-26 08:00:00');
        $day3_end1      = Carbon::parse('2025-07-26 11:59:59');
        $day4_start1    = Carbon::parse('2025-07-27 08:00:00');
        $day4_end1      = Carbon::parse('2025-07-27 11:59:59');
        $nownow = Carbon::now();
        $cond1 = $nownow->between($day1_start1, $day1_end1) 
		|| $nownow->between($day2_start1, $day2_end1)
		|| $nownow->between($day3_start1, $day3_end1)
		|| $nownow->between($day4_start1, $day4_end1);

	$schedule->command('export:CheckIn  96 --renew=1')->everyMinute()->when($cond1);    //ceocamp
	$schedule->command('export:CheckIn 102 --renew=1')->everyMinute()->when($cond1);    //ecamp_c
	$schedule->command('export:CheckIn 106 --renew=1')->everyMinute()->when($cond1);    //ecamp_n        
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
