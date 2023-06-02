<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\LfiFailedEvent;

class Lfi extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new LfiFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

}
