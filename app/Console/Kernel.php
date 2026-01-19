<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('projects:notify-overdue')->dailyAt('5:00')->withoutOverlapping();
        $schedule->command('projects:notify-unstarted')->dailyAt('5:00')->withoutOverlapping();
        $schedule->command('files:clean-tmp')->dailyAt('05:00')->withoutOverlapping();
        $schedule->command('horizon')->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
