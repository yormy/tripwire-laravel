<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Middleware;
use Yormy\TripwireLaravel\Observers\Events\SqliFailedEvent;
use Yormy\TripwireLaravel\Observers\Events\SwearFailedEvent;

class Sqli extends Middleware
{

    protected function attackFound(array $violations): void
    {
        event(new SqliFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

}
