<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        // $schedule->command('check:Accounting tcamp')->dailyAt("16:30");
        // $schedule->command('check:Accounting hcamp')->dailyAt("16:30");
        // $schedule->command('check:Accounting ycamp')->dailyAt("16:30");
        // $schedule->command('check:Accounting ceocamp')->dailyAt("16:30");
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();

        //$schedule->command('gen:BankSecondBarcode 79')->dailyAt("0:28"); //ceocamp
        //$schedule->command('import:Form 96')->dailyAt("0:29"); //ceocamp
        $schedule->command('export:Applicant 96')->dailyAt("12:30"); //ceocamp,1st
        $schedule->command('export:Applicant 96')->dailyAt("0:30"); //ceocamp,2nd
        $schedule->command('export:Applicant 97')->dailyAt("0:45"); //ceovcamp
        $schedule->command('export:Applicant 102')->dailyAt("1:00"); //ecamp_c
        $schedule->command('export:Applicant 100')->dailyAt("1:30"); //ecamp_s
        $schedule->command('export:Applicant 106')->dailyAt("2:00"); //ecamp_n
        $schedule->command('export:Applicant 103')->dailyAt("2:30"); //evcamp_c
        $schedule->command('export:Applicant 101')->dailyAt("3:00"); //evcamp_s
        $schedule->command('export:Applicant 107')->dailyAt("3:30"); //evcamp_n

        //$schedule->command('export:CheckIn 77')->everyThreeMinutes(); //ecamp
        //$schedule->command('export:CheckIn 79')->everyMinute(); //ceocamp
        //$schedule->command('export:CheckIn 81')->everyMinute(); //ycamp
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
