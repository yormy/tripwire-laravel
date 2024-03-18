<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class ResetService
{
    public static function run(Request $request): bool
    {
        if (! config('tripwire.reset.enabled')) {
            return false;
        }

        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint = $requestSourceClass::getBrowserFingerprint(); // @phpstan-ignore-line

        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request); // @phpstan-ignore-line

        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request); // @phpstan-ignore-line
        $userType = $userClass::getType($request); // @phpstan-ignore-line

        $softDelete = (bool) config('tripwire.reset.soft_delete');
        $logRepository = new LogRepository();
        $logRepository->resetIp($ipAddress, $softDelete);
        $logRepository->resetBrowser($browserFingerprint, $softDelete);
        $logRepository->resetUser($userId, $userType, $softDelete);

        $blockRepository = new BlockRepository();
        $blockRepository->resetIp($ipAddress, $softDelete);
        $blockRepository->resetBrowser($browserFingerprint, $softDelete);
        $blockRepository->resetUser($userId, $userType, $softDelete);

        return true;
    }
}
