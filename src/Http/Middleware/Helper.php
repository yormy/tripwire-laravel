<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

trait Helper
{
    public Request $request;
    public ?string $middleware = null;

    public Config $config;

    public function isWhitelist(string $ipAddress = null)
    {
        if (!$ipAddress) {
            $ipAddressClass = config('tripwire.services.ip_address');
            $ipAddress = $ipAddressClass::get($this->request);
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

}
