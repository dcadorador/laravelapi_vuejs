<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Process\CheckDueSync;
use App\Jobs\MachshipCacheJob;
use App\Process\UpdateSourceIntegration;

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
        $schedule->call(function () {
            new CheckDueSync();
        })->everyMinute()->runInBackground();
        $schedule->call(function () {
            new UpdateSourceIntegration();
        })->everyFifteenMinutes()->runInBackground();
        $schedule->job(new MachshipCacheJob)->daily()->runInBackground();
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
