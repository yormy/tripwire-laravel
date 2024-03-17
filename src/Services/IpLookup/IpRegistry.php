<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\IpLookup;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\Services\Interfaces\IpLookupInterface;

class IpRegistry extends BaseLookup implements IpLookupInterface
{
    public function __construct(
        private readonly string $apiKey
    ) {
        // ...
    }

    public function get(string $ipAddress): ?GeoLocation
    {
        $response = $this->getResponse('https://api.ipregistry.co/'.$ipAddress.'?key='.$this->apiKey);

        if (! is_object($response) || $this->hasFailed($response)) {
            return null;
        }

        return new GeoLocation(
            $response->location->continent->name,
            $response->location->country->name,
            $response->location->region->name,
            $response->location->city
        );
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    private function hasFailed($response): bool
    {
        return false; //todo determine if call failed
    }
}
