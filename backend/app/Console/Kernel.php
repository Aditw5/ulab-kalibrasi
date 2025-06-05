<?php

namespace App\Console;

use App\Console\Commands\AkomodasiCron;
use App\Console\Commands\ReservasiReminderCron;
use App\Console\Commands\SaldoAwalHarianCron;
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
        AkomodasiCron::class,
        SaldoAwalHarianCron::class,
        ReservasiReminderCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         CARA RUNNING DILOKAL 
         # php artisan schedule:work

         LIST SCHEDULER
         # php artisan schedule:list
        */ 

        
        $schedule->command('akomodasi:cron')->cron('* * * * *');
        $schedule->command('saldoawalharian:cron')
        // ->dailyAt('23:00')
        ->cron('* * * * *')
        // ->everyMinute()
        ->timezone(config('app.timezone'))
        ->after(function () {
            $fp = fopen(storage_path('logs/scheduler.log'), 'a');//opens file in append mode  
            fwrite($fp,PHP_EOL. "Post Saldo awal berhasil : ". date('Y-m-d H:i:s') );   
            fclose($fp);  
        });
        // $schedule->command('inspire')->hourly();
        $schedule->command('reservasireminder:cron')->cron('* * * * *');
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
