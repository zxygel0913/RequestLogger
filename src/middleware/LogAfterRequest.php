<?php
namespace Zxygel0913\RequestLogger\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class LogAfterRequest
{
    public function handle($request, \Closure  $next)
	{
        $user = $this->getUser();
        $MAC = exec('getmac');
        
        $MAC = strtok($MAC, ' ');
		Log::channel(config('requestLogs.channel')?? 'daily')->info('app.requests', [
            'user_id' => $user ? $user->id : $user,
            'ip' => config('requestLogs.logs.ip') ? Request::ip() : null,
            'url' => config('requestLogs.logs.url') ? Request::fullUrl() : null,
            'request_method' => Request::method(),
            'request' => config('requestLogs.logs.request') ? Request::except(config('requestLogs.logs.request_except')) : null,
            'mac_adrress' => $MAC,
            'browser' => $this->getBrowser()
        ]);
		return $next($request);
	}

	public function terminate($request, $response)
	{
		Log::channel(config('requestLogs.channel')?? 'daily')->info('app.response', [
            'response' => config('requestLogs.logs.response') ? $response : null
        ]);
	}

    public function getBrowser() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = "N/A";
        
        $browsers = array(
        '/msie/i' => 'Internet explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/mobile/i' => 'Mobile browser'
        );
        
        foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) { $browser = $value; }
        }
        
        return $browser;
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
