<?php
namespace Zxygel0913\RequestLogger\Middleware;
use Illuminate\Support\Facades\Log;

class LogAfterRequest
{
    public function handle($request, \Closure  $next)
	{
		return $next($request);
	}

	public function terminate($request, $response)
	{
		Log::channel(config('requestLogs.channel')?? 'daily')->info('app.requests', [
            'headers' => config('requestLogs.logs.headers') ? $request->headers->all() : null,
            'path' => config('requestLogs.logs.path') ? $request->getRequestUri() : null,
            'ip' => config('requestLogs.logs.ip') ? $request->ip() : null,
            'request_method' => config('requestLogs.logs.request_method') ? $request->method() : null,
            'request' => config('requestLogs.logs.request') ? $request->except(config('requestLogs.logs.request_except')) : null,
            'response' => config('requestLogs.logs.response') ? $response : null
    ]);
	}

}
