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
        $schedule->command('app:fetch-articles-news-api')->daily();
        $schedule->command('fetch:bbc_articles')->daily();
        $schedule->command('fetch:nyt_articles')->daily();
        /** To check result as well daily base then we can use this one as well */
        /**
         *  $schedule->command('fetch:news_api_articles')->daily()->sendOutputTo(storage_path('logs/news_api_articles.log'));
         * $schedule->command('fetch:bbc_articles')->daily()->sendOutputTo(storage_path('logs/bbc_articles.log'));
         * $schedule->command('fetch:nyt_articles')->daily()->sendOutputTo(storage_path('logs/nyt_articles.log'));
         */
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
