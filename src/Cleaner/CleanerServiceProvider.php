<?php

namespace MisterPaladin\Cleaner;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class CleanerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('cleaner:run')->everyMinute();
        });
    }
    
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/cleaner.php' => config_path('cleaner.php'),
        ], 'cleaner');
        $this->commands([
            'cleaner:run',
        ]);
        $this->app->bind('cleaner:run', function ($app) {
            return new \MisterPaladin\Cleaner\Commands\Cleaner;
        });
    }
}
