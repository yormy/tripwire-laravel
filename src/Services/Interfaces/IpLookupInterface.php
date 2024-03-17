<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\Interfaces;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;

interface IpLookupInterface
{
    public function get(string $ipAddress): ?GeoLocation;
}
