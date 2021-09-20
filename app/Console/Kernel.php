<?php

namespace App\Console;

use App\Console\Commands\RegisteredUsers;
use App\Console\Commands\LoggedUsers;
use App\Console\Commands\ScenesVisited;
use App\Console\Commands\ClickEvents;
use App\Console\Commands\Points;
use App\Console\Commands\SyncPosterGallery;
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
        RegisteredUsers::class,
        LoggedUsers::class,
        ScenesVisited::class,
        ClickEvents::class,
        Points::class,
        SyncPosterGallery::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('sync:registeredusers')->everyMinute()->withoutOverlapping();
        $schedule->command('sync:loggedusers')->everyMinute()->withoutOverlapping();
        $schedule->command('sync:scenesvisited')->everyMinute()->withoutOverlapping();
        $schedule->command('sync:clickevents')->everyMinute()->withoutOverlapping();
        $schedule->command('sync:points')->everyMinute()->withoutOverlapping();
        $schedule->command('sync:postergallery')->everyMinute()->withoutOverlapping();
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
