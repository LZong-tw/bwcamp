<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // \Schema::defaultStringLength(191);
        if ($this->app->runningInConsole()) {
            $this->app['queue']->failing(function (\Illuminate\Queue\Events\JobFailed $event) {
                if (strpos($event->exception, 'response code 250')) {
                    report(new \Exception('Got swift 250 error, restarting queue.'));
        
                    sleep(5);
                    \Artisan::call('queue:restart');
                }
            });
        }
    }
}
