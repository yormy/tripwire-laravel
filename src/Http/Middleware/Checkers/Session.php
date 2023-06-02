<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\SessionFailedEvent;

class Session extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new SessionFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

}
