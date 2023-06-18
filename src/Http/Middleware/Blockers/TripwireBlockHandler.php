<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Blockers;


use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
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
            $config = JsonResponseConfig::makeFromArray(config('tripwire.block_response.json'));
            $blockResponse = new ConfigResponse($config);
            $respond = new ResponseDeterminer($blockResponse);

            return $respond->respondWithJson(['blocked_until' => $blockedUntil]);
        }

        $config = HtmlResponseConfig::makeFromArray(config('tripwire.block_response.html'));
        $blockResponse = new ConfigResponse($config);
        $respond = new ResponseDeterminer($blockResponse);

        return $respond->respondWithHtml(['blocked_until' => $blockedUntil]);
    }
}
