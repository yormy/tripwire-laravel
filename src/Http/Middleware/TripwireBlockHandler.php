<?php

namespace Yormy\TripwireLaravel\Http\Middleware;


use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Carbon\Carbon;

abstract class TripwireBlockHandler
{

    protected abstract function isBlockedUntil(): ?Carbon;


    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$blockedUntil = $this->isBlockedUntil()) {
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
