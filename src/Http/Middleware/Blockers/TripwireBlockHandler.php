<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Blockers;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\Services\UrlTester;

abstract class TripwireBlockHandler
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (UrlTester::skipUrl($request, config('tripwire.urls'))) {
            return $next($request);
        }

        if (! $blockedUntil = $this->isBlockedUntil($request)) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            $config = JsonResponseConfig::makeFromArray(config('tripwire.block_response.json'));
            $respond = new ResponseDeterminer($config);

            return $respond->respondWithJson(['blocked_until' => $blockedUntil]);
        }

        $config = HtmlResponseConfig::makeFromArray(config('tripwire.block_response.html'));
        $respond = new ResponseDeterminer($config);

        return $respond->respondWithHtml(['blocked_until' => $blockedUntil]);
    }

    abstract protected function isBlockedUntil(Request $request): ?Carbon;
}
