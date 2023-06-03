<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Blockers;


use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Carbon\Carbon;
use Yormy\TripwireLaravel\Services\UrlTester;

abstract class TripwireBlockHandler
{

    protected abstract function isBlockedUntil(Request $request): ?Carbon;

    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (UrlTester::skipUrl($request, config('tripwire.urls'))) {
            return  $next($request);
        }

        if (!$blockedUntil = $this->isBlockedUntil($request)) {
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
