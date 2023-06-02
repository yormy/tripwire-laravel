<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\SqliFailedEvent;

class Sqli extends BaseChecker
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
