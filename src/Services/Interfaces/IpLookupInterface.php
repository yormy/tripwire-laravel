<?php

namespace Yormy\TripwireLaravel\Services\Interfaces;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;

interface IpLookupInterface
{
    public function get(string $ipAddress): ?GeoLocation;
}
