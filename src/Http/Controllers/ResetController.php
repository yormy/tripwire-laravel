<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class ResetController extends controller
{
    public function reset(Request $request)
    {
        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint =$requestSourceClass::getBrowserFingerprint();

        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request);
        $userType = $userClass::getType($request);

        $logRepository = new LogRepository();
        $logRepository->resetIp($ipAddress);
        $logRepository->resetBrowser($browserFingerprint);
        $logRepository->resetUser($userId, $userType);

        return response()->json(['logs cleared']);
    }
}
