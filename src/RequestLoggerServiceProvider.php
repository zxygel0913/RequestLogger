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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app('log')->stack([
            'channels' => [
                'system_daily' => [
                    'driver' => 'daily',
                    'path' => storage_path('logs/app/request-logs/laravel.log'),
                    'level' => 'debug',
                    'days' => 30,
                ]
            ]
        ]);
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('api', LogAfterRequest::class);
    }
}