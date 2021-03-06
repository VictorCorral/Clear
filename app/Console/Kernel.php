<?php

namespace CodeDay\Clear\Console;

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
        Commands\ClearBeanstalkdQueueCommand::class,
        Commands\DispatchTransactionalEmailsCommand::class,
        Commands\DispatchWaiverSyncCommand::class,
        Commands\SyncPhotosCommand::class,
        Commands\CronCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dispatch:transactional-emails')
                ->everyMinute()
                ->withoutOverlapping();

        $schedule->command('dispatch:waiver-sync')
                ->dailyAt('17:00')
                ->withoutOverlapping();

        $schedule->command('cron')
                 ->everyMinute()
                 ->withoutOverlapping();
    }
}
