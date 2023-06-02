<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\GeoFailedEvent;
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
//        $places = ['continents', 'regions', 'countries', 'cities'];

        if (! $location = $this->getLocation()) {
            return false;
        }







        $agents = $this->config->agents;
        if (empty($agents)) {
            return false;
        }

        if (!empty($violations)) {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    protected function getLocation()
    {
        $service = $this->config->custom['service'];
        $apiKey = '';
        //env('IPSTACK_KEY')
        //env('IPINFO_KEY')
        $ipLookup = new IpLookup(IpAddress::get($this->request), $service, $apiKey);
        $location = $ipLookup->get();
        dd($location);
        dd('ooooo');
    }
}
