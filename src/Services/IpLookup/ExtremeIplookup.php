<?php

namespace Yormy\TripwireLaravel\Services\IpLookup;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\Services\Interfaces\IpLookupInterface;

class ExtremeIplookup extends BaseLookup implements IpLookupInterface
{
    public function __construct(
        private readonly string $apiKey
    ) {
        // ...
    }

    public function get(string $ipAddress): ?GeoLocation
    {
        $response = $this->getResponse('https://extreme-ip-lookup.com/json/'.$ipAddress);

        if (! is_object($response) || $this->hasFailed($response)) {
            return null;
        }

        return new GeoLocation(
            $response->continent,
            $response->country,
            $response->region,
            $response->city
        );
    }

    private function hasFailed($response): bool
    {
        return $response->status === 'fail';
    }
}
