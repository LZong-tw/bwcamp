<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

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
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
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
        // In a Model (for example, see Camp/Batch),
        // if a field is casted to [date],
        // its default print format is format('Y-m-d H:i:s')
        // the following line changes the default to format('Y-m-d')
        // 讓所有的日期物件在印出來時都預設不顯示時間
        Carbon::setToStringFormat('Y-m-d');
    }
}
