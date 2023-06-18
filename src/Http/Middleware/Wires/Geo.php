<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\GeoFailedEvent;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\IpLookup;

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
        $places = ['continents', 'regions', 'countries', 'cities'];

        if (! $location = $this->getLocation()) {
            return false;
        }

        $location = new \StdClass();
        $location->continent ='Europe';

        $continent = 'Europe';
        $continentsGuards = $this->config->tripwires['continents'];

        $violations = [];
        if($this->isGuardAttack($continent, $continentsGuards)) {
            $violations[] = $continent;
        }

        if (!empty($violations)) {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    protected function getLocation()
    {
        $service = $this->config->tripwires['service'];
        $apiKey = '--';
        $ipLookup = new IpLookup(IpAddress::get($this->request), $service, $apiKey);
        $location = $ipLookup->get();
    }
}
