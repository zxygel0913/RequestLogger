<?php

namespace Zxygel0913\RequestLogger;

use Illuminate\Support\ServiceProvider;
use Zxygel0913\RequestLogger\Middleware\LogAfterRequest;

class RequestLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/requestLogs.php' => config_path('requestLogs.php')
        ], 'requestLogs');
        $this->app->make('config')->set('logging.channels.system_daily', [
            'driver' => 'daily',
            'path' => storage_path('logs/app/request-logs/laravel.logg'),
            'level' => 'debug',
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('api', LogAfterRequest::class);
    }
}