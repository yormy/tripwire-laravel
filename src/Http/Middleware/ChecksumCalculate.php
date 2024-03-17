<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\Services\HashService;

/**
 * Calculate the checksum before any request modifications
 * This need to be the very first in your middelware set before modifying any request item
 */
class ChecksumCalculate
{
    public function handle(Request $request, Closure $next): mixed
    {
        $request = $this->calculate($request);

        return $this->validate($request, $next);
    }

    public function calculate(Request $request): Request
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $data = $request->except(array_keys($request->query()));
        $requestJson = json_encode($data, JSON_UNESCAPED_UNICODE);
        $requestCleaned = $requestJson;
        $requestCleaned = preg_replace('/[^a-z0-9]/', '', $requestCleaned);

        $checksumDetails = WireDetailsConfig::makeFromArray(config('tripwire_wires.checksum'));
        $request->request->add([$checksumDetails->config['serverside_calculated'] => HashService::create($requestCleaned)]);

        return $request;
    }

    public function validate(Request $request, Closure $next)
    {
        $validate = new ChecksumValidateWire();

        return $validate->handle($request, $next);
    }
}
