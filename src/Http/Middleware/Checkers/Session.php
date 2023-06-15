<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\SessionFailedEvent;

class Session extends BaseChecker
{
    public const NAME = 'session';

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new SessionFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }

}
