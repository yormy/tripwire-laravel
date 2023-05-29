<?php

namespace Yormy\TripwireLaravel\Tests\Fakes;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\Services\Interfaces\IpLookupInterface;

class FakeIpLookup implements IpLookupInterface
{
    public function get(string $ipAddress): ?GeoLocation
    {
        return new GeoLocation(
            'CONTINENT',
            'COUNTRY',
            'REGION',
            'CITY'
        );
    }
}
