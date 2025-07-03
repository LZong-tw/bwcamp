<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Permission\PermissionCache;
use App\Services\Permission\PermissionFactory;
use App\Services\Permission\EnhancedPermissionService;
use App\Services\Permission\CacheInvalidationService;
use App\Observers\RoleObserver;
use App\Observers\PermissionObserver;
use App\Observers\UserObserver;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

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
        
        // Register permission services
        $this->app->singleton(PermissionCache::class);
        $this->app->singleton(PermissionFactory::class);
        $this->app->singleton(CacheInvalidationService::class);
        $this->app->singleton(EnhancedPermissionService::class, function ($app) {
            return new EnhancedPermissionService(
                $app->make(PermissionCache::class),
                $app->make(PermissionFactory::class)
            );
        });
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

        // Register model observers for cache invalidation
        Role::observe(RoleObserver::class);
        Permission::observe(PermissionObserver::class);
        User::observe(UserObserver::class);
    }
}
