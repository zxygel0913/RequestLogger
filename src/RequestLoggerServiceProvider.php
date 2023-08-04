<?php

namespace Zxygel0913\QueryLoggerMiddleware;

use Illuminate\Support\ServiceProvider;
use Zxygel0913\QueryLoggerMiddleware\Middleware\LogQueries; // Import the LogQueries middleware

class QueryLoggerMiddlewareServiceProvider extends ServiceProvider
{
    
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/query-logger.php', 'query-logger');

        $this->publishes([
            __DIR__.'/config/query-logger.php' => config_path('query-logger.php'),
        ], 'config');
    }

    public function boot()
    {
        $this->app['router']->aliasMiddleware('logqueries', LogQueries::class);
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/query-logger.php' => config_path('query-logger.php'),
            ], 'query-logger-config'); // Use the correct tag here
        }
    }
}
