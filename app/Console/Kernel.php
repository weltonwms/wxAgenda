<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\InativarAlunosSemRecarga;
use App\Console\Commands\TesteLog;



class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule
            ->command(InativarAlunosSemRecarga::class)
            ->dailyAt('05:00')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();

        /*$schedule
        ->command(TesteLog::class)
        ->everyMinute()
        ->withoutOverlapping()
        ->onOneServer()
        ->runInBackground();
        */
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
