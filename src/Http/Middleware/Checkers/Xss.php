<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\XssFailedEvent;

class Xss extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new XssFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

}
