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

        $continentsGuards = $this->config->tripwires()['continents'];
        $regionGuards = $this->config->tripwires()['regions'];
        $countryGuards = $this->config->tripwires()['countries'];
        $cityGuards = $this->config->tripwires()['cities'];

        $violations = [];
        if ($this->isGuardAttack($location->continent, $continentsGuards)) {
            $violations[] = $location->continent;
        }

        if ($this->isGuardAttack($location->region, $regionGuards)) {
            $violations[] = $location->region;
        }

        if ($this->isGuardAttack($location->country, $countryGuards)) {
            $violations[] = $location->country;
        }


        if ($this->isGuardAttack($location->city, $cityGuards)) {
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
