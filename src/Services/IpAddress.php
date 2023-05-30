<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request ;

class IpAddress
{
    public static function get(Request $request): string
    {
        if ($cloudflarePassthroughIp = $request->header('CF_CONNECTING_IP')) {
            return $cloudflarePassthroughIp;
        }

        return $request->ip();
    }
}
