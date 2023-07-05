<?php

namespace Yormy\TripwireLaravel\Services\IpLookup;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\Services\Interfaces\IpLookupInterface;

class IpApi extends BaseLookup implements IpLookupInterface
{
    public function __construct(
        private readonly string $apiKey
    ) {
        // ...
    }

    public function get(string $ipAddress): ?GeoLocation
    {
        $response = $this->getResponse('http://ip-api.com/json/'.$ipAddress.'?fields=continent,country,regionName,city');

        if (! is_object($response) || $this->hasFailed($response)) {
            return null;
        }

        $location = new GeoLocation(
            $response->continent,
            $response->country,
            $response->regionName,
            $response->city
        );

        return $location;
    }

    private function hasFailed($response): bool
    {
        return false; //todo determine if call failed
    }
}