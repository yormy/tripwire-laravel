<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\GeoLocation;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\GeoFailedEvent;
use Yormy\TripwireLaravel\Services\IpAddress;

class Geo extends BaseWire
{
    public const NAME = 'geo';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new GeoFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        if (! $location = $this->getLocation()) {
            return false;
        }

        $continentsFilter = $this->config->tripwires()['continents'];
        $regionFilter = $this->config->tripwires()['regions'];
        $countryFilter = $this->config->tripwires()['countries'];
        $cityFilter = $this->config->tripwires()['cities'];

        $violations = [];
        if ($this->isFilterAttack($location->continent, $continentsFilter)) {
            $violations[] = $location->continent;
        }

        if ($this->isFilterAttack($location->region, $regionFilter)) {
            $violations[] = $location->region;
        }

        if ($this->isFilterAttack($location->country, $countryFilter)) {
            $violations[] = $location->country;
        }

        if ($this->isFilterAttack($location->city, $cityFilter)) {
            $violations[] = $location->city;
        }

        return ! empty($violations);
    }

    protected function getLocation(): ?GeoLocation
    {
        $service = $this->config->tripwires()['service'];
        $apiKey = $this->config->tripwires()['api_key'];
        $ipAddress = IpAddress::get($this->request);

        $ipLookup = new $service($apiKey);

        return $ipLookup->get($ipAddress);
    }
}
