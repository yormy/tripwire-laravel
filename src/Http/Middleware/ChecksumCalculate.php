<?php

namespace Yormy\TripwireLaravel\Http\Middleware;


use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Services\HashService;
use Closure;
use Illuminate\Http\Request;


/**
 * Calculate the checksum before any request modifications
 * This need to be the very first in your middelware set before modifying any request item
 */
class ChecksumCalculate
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        throw new RequestChecksumFailedException();



        /** @psalm-suppress PossiblyInvalidArgument */
        $data = $request->except(array_keys($request->query()));
        $requestJson = json_encode($data, JSON_UNESCAPED_UNICODE);
        $requestCleaned = $requestJson;
        $requestCleaned = preg_replace('/[^a-z0-9]/', '', $requestCleaned);

        $request->request->add([config('tripwire.checksums.serverside_calculated') => HashService::create($requestCleaned)]);

        return  $next($request);
    }
}
