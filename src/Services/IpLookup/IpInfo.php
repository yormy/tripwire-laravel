<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\IpLookup;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\Services\Interfaces\IpLookupInterface;

class IpInfo extends BaseLookup implements IpLookupInterface
{
    public function __construct(
        private readonly string $apiKey
    ) {
        // ...
    }

    public function get(string $ipAddress): ?GeoLocation
    {
        $response = $this->getResponse('https://ipinfo.io/'.$ipAddress.'/geo?token='.$this->apiKey);

        if (! is_object($response) || $this->hasFailed($response)) {
            return null;
        }

        return new GeoLocation(
            '',
            $response->country,
            $response->region,
            $response->city
        );
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    private function hasFailed(\StdClass $response): bool
    {
        return false; //todo determine if call failed
    }
}
