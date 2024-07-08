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
        $schedule->command('check:Accounting ceocamp')->dailyAt("16:30");
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();

        $schedule->command('gen:BankSecondBarcode 79')->dailyAt("0:28"); //ceocamp
        $schedule->command('import:Status 79')->dailyAt("0:29"); //ceocamp
        $schedule->command('export:Applicant 79')->dailyAt("0:30"); //ceocamp
        $schedule->command('export:Applicant 80')->dailyAt("1:00"); //ceovcamp
        $schedule->command('export:Applicant 77')->dailyAt("1:30"); //ecamp
        $schedule->command('export:Applicant 78')->dailyAt("2:30"); //evcamp

        $schedule->command('export:CheckIn 37')->everyThreeMinutes(); //ecamp
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
