<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripwireBlockHandlerBrowser extends TripwireBlockHandler
{
    protected function isBlockedUntil(Request $request): ?Carbon
    {
        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint =$requestSourceClass::getBrowserFingerprint();

        $blockRepository = new BlockRepository();
        return $blockRepository->isBrowserBlockedUntil($browserFingerprint);
    }
}
