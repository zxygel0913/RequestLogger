<?php
namespace Zxygel0913\RequestLogger\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class LogAfterRequest
{
    public function handle($request, \Closure  $next)
	{
		return $next($request);
	}

	public function terminate($request, $response)
	{
		Log::channel(config('requestLogs.channel')?? 'daily')->info('app.requests', [
            'user_agent' => Request::header('User-Agent'),
            'ip' => config('requestLogs.logs.ip') ? Request::ip() : null,
            'url' => config('requestLogs.logs.url') ? Request::fullUrl() : null,
            'request_method' => Request::method(),
            'request' => config('requestLogs.logs.request') ? Request::except(config('requestLogs.logs.request_except')) : null,
            'response' => config('requestLogs.logs.response') ? $response : null
    ]);
	}

}
