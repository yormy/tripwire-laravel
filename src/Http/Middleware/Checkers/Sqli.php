<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\SqliFailedEvent;

class Sqli extends BaseChecker
{

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new SqliFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }

}
