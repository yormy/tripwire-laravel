<?php

namespace Yormy\TripwireLaravel\Services\IpLookup;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\Services\Interfaces\IpLookupInterface;

class IpData extends BaseLookup implements IpLookupInterface
{
    public function __construct(
        private readonly string $apiKey
    ) {
        // ...
    }

    public function get(string $ipAddress): ?GeoLocation
    {
        $response = $this->getResponse('https://api.ipdata.co/'.$ipAddress.'?api-key='.$this->apiKey);

        if (! is_object($response) || $this->hasFailed($response)) {
            return null;
        }

        $location = new GeoLocation(
            $response->continent_name,
            $response->country_name,
            $response->region_name,
            $response->city
        );

        return $location;
    }

    private function hasFailed($response): bool
    {
        return false; //todo determine if call failed
    }
}