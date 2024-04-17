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
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();
        $schedule->command('export:Applicant 77')->dailyAt("2:10");
        $schedule->command('export:Applicant 79')->dailyAt("2:15");
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
