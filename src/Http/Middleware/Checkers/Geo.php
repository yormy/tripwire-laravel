<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\GeoFailedEvent;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\IpLookup;

class Geo extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new GeoFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

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
        $continentsGuards = $this->config->custom['continents'];

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
        $service = $this->config->custom['service'];
        $apiKey = '--';
        $ipLookup = new IpLookup(IpAddress::get($this->request), $service, $apiKey);
        $location = $ipLookup->get();
    }
}
