<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Yormy\TripwireLaravel\Services\IpAddress;

trait Helper
{
    public Request $request;
    public string|null $middleware = null;
    public int|null $user_id = null;

    public Config $config;

    public function isWhitelist(string $ipAddress = null)
    {
        if (!$ipAddress) {
            $ipAddress = IpAddress::get($this->request);
        }

        return IpUtils::checkIp($ipAddress, config('tripwire.whitelist'));
    }

    public function isInput($name, $middleware = null)
    {
        $middleware = $middleware ?? $this->middleware;

        if (! $inputs = config('firewall.middleware.' . $middleware . '.inputs')) {
            return true;
        }

        if (! empty($inputs['only']) && ! in_array((string) $name, (array) $inputs['only'])) {
            return false;
        }

        return ! in_array((string) $name, (array) $inputs['except']);
    }

//    public function log($middleware = null, $user_id = null, $level = 'medium')
//    {
//        $middleware = $middleware ?? $this->middleware;
//        $user_id = $user_id ?? $this->user_id;
//
//        $model = config('firewall.models.log', Log::class);
//
//        $input = urldecode(http_build_query($this->request->input()));
//
//        return $model::create([
//            'ip' => $this->ip(),
//            'level' => $level,
//            'middleware' => $middleware,
//            'user_id' => $user_id,
//            'url' => $this->request->fullUrl(),
//            'referrer' => substr($this->request->server('HTTP_REFERER'), 0, 191) ?: 'NULL',
//            'request' => substr($input, 0, config('firewall.log.max_request_size')),
//        ]);
//    }

}
