<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripwireBlockHandlerUser extends TripwireBlockHandler
{

    protected function isBlockedUntil(Request $request): ?Carbon
    {
        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request);
        $userType = $userClass::getType($request);

        $blockRepository = new BlockRepository();
        return $blockRepository->isUserBlockedUntil($userId, $userType);
    }
}
