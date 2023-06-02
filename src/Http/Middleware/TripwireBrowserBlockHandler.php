<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Closure;
use Carbon\Carbon;

class TripwireBrowserBlockHandler extends TripwireBlockHandler
{
    protected function isBlockedUntil(): ?Carbon
    {
        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint =$requestSourceClass::getBrowserFingerprint();

        $blockRepository = new BlockRepository();
        return $blockRepository->isBrowserBlockedUntil($browserFingerprint);
    }
}
