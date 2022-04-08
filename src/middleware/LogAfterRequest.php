<?php
namespace Zxygel0913\RequestLogger\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class LogAfterRequest
{
    public function handle($request, \Closure  $next)
	{
		return $next($request);
	}

	public function terminate($request, $response)
	{
        $user = $this->getUser();
		Log::channel(config('requestLogs.channel')?? 'daily')->info('app.requests', [
            'user_id' => $user ? $user->id : $user,
            'user_agent' => Request::header('User-Agent'),
            'ip' => config('requestLogs.logs.ip') ? Request::ip() : null,
            'url' => config('requestLogs.logs.url') ? Request::fullUrl() : null,
            'request_method' => Request::method(),
            'request' => config('requestLogs.logs.request') ? Request::except(config('requestLogs.logs.request_except')) : null,
            'response' => config('requestLogs.logs.response') ? $response : null
        ]);
	}

    public function getUser()
    {
        $guards = ['api','web'];

        foreach ($guards as $guard) {
            try {
                $authenticated = Auth::guard($guard);
            } catch (\Exception $exception) {
                continue;
            }

            if ($authenticated) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }

}
