<?php

namespace Yormy\TripwireLaravel\Http\Middleware;


use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;

class TripwireIpBlockHandler
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $blockRepository = new BlockRepository();
        if (!$blockedUntil = $blockRepository->isIpBlockedUntil($ipAddress)) {
            return  $next($request);
        }

        if ($request->wantsJson()) {
            $blockResponse = new ConfigResponse($request, config('tripwire.block_response.json'));
            $respond = new ResponseDeterminer($blockResponse);
            return $respond->respondWithJson(['blocked_until' => $blockedUntil]);
        }

        $blockResponse = new ConfigResponse($request, config('tripwire.block_response.html'));
        $respond = new ResponseDeterminer($blockResponse);
        return $respond->respondWithHtml(['blocked_until' => $blockedUntil]);
    }
}
