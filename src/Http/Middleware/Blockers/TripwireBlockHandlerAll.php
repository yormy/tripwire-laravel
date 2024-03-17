<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Blockers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

class TripwireBlockHandlerAll extends TripwireBlockHandler
{
    protected function isBlockedUntil(Request $request): ?Carbon
    {
        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint = $requestSourceClass::getBrowserFingerprint();

        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request);
        $userType = $userClass::getType($request);

        $blockRepository = new BlockRepository();

        return $blockRepository->isAnyBlockedUntil(
            $ipAddress,
            $browserFingerprint,
            $userId,
            $userType,
        );
    }
}
