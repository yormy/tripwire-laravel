<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects;

class GeoLocation
{
    public function __construct(
        public readonly string $continent,
        public readonly string $country,
        public readonly string $region,
        public readonly string $city
    ) {
        // ...
    }
}
