<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\LfiFailedEvent;

class Lfi extends BaseChecker
{
    public const NAME = 'lfi';

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new LfiFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }

}
