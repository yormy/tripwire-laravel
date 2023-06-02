<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripwireBlockHandlerIp extends TripwireBlockHandler
{

    protected function isBlockedUntil(Request $request): ?Carbon
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $blockRepository = new BlockRepository();
        return $blockRepository->isIpBlockedUntil($ipAddress);
    }
}
