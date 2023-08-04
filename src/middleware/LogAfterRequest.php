<?php

namespace Zxygel0913\QueryLoggerMiddleware\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogQueries
{
    protected $loggedQueries = [];

    public function handle(Request $request, Closure $next): Response
    {
        // Check if query logging is enabled for this request
        if (config('app.debug') && config('query-logger.log_queries')) {
            DB::listen(function ($query) use ($request) {
                // Check if this query has already been logged recently
                if ($this->shouldLogQuery($query)) {
                    $this->logQuery($query, $request);
                }
            });
        }

        return $next($request);
    }

    protected function shouldLogQuery($query)
    {
        $hash = md5($query->sql . serialize($query->bindings));

        if (in_array($hash, $this->loggedQueries)) {
            return false;
        }

        $this->loggedQueries[] = $hash;

        // Remove old hashes to prevent memory growth
        $this->loggedQueries = array_slice($this->loggedQueries, -100);

        return true;
    }

    protected function logQuery($query, $request)
    {
        $urlOrigin = $request->headers->get('origin') ?? 'Unknown Origin';
        $ipAddress = $request->ip();

        $executionTime = round($query->time / 1000, 2); // Convert to seconds

        // Log only queries that take longer than 100ms
        if ($executionTime > 0.1) {
            Log::warning('Slow Query: ' . $query->sql, [
                'bindings' => $query->bindings,
                'execution_time' => $executionTime . 's',
                'url_origin' => $urlOrigin,
                'ip_address' => $ipAddress,
            ]);
        } else {
            Log::debug('Query: ' . $query->sql, [
                'bindings' => $query->bindings,
                'execution_time' => $executionTime . 's',
                'url_origin' => $urlOrigin,
                'ip_address' => $ipAddress,
            ]);
        }
    }
    
}
