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
        // 充值48小时后自动更新充值成功
        \App\Console\Commands\RechargeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->myCommands($schedule);
        $this->mySchedules($schedule);
    }

    private function myCommands(Schedule $schedule)
    {
        // 充值48小时后自动更新充值成功
        $schedule->command('RechargeCommand')->cron('20 * * * *')->withoutOverlapping()->appendOutputTo(storage_path() . '/logs/RechargeCommand.log');
    }

    /**
     *  内置任务处理
     *
     * @param Schedule $schedule
     */
    private function mySchedules(Schedule $schedule)
    {
        //
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
