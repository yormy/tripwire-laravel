<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\Jobs\AddBlockJob;

class BlockIfNeeded
{
    public static function run(?Request $request, PunishConfig $punish, bool $trainingMode = false): void
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request ?? null); // @phpstan-ignore-line
        $userClass = config('tripwire.services.user');

        $userId = 0;
        $userType = '';
        if ($request ?? false) {
            $userId = $userClass::getId($request);  // @phpstan-ignore-line
            $userType = $userClass::getType($request);  // @phpstan-ignore-line
        }

        AddBlockJob::dispatch(
            ipAddress: $ipAddress,
            userId: $userId,
            userType: $userType,
            withinMinutes: $punish->withinMinutes,
            thresholdScore: $punish->score,
            penaltySeconds: $punish->penaltySeconds,
            trainingMode: $trainingMode,
        );
    }
}
