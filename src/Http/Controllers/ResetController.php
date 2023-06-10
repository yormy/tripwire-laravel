<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;
use Yormy\TripwireLaravel\Services\ResetUrl;

class ResetController extends controller
{
    public function reset(Request $request)
    {
        if (!config('tripwire.reset.allowed')) {
            return;
        }

        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint =$requestSourceClass::getBrowserFingerprint();

        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request);
        $userType = $userClass::getType($request);

        $softDelete = (bool)config('tripwire.reset.soft_delete');
        $logRepository = new LogRepository();
        $logRepository->resetIp($ipAddress, $softDelete);
        $logRepository->resetBrowser($browserFingerprint, $softDelete);
        $logRepository->resetUser($userId, $userType, $softDelete);

        $blockRepository = new BlockRepository();
        $blockRepository->resetIp($ipAddress, $softDelete);
        $blockRepository->resetBrowser($browserFingerprint, $softDelete);
        $blockRepository->resetUser($userId, $userType, $softDelete);

        return response()->json(['logs cleared']);
    }

    public function getKey()
    {
        $url = ResetUrl::get();

        return response()->json(['url' => $url]);
    }
}
