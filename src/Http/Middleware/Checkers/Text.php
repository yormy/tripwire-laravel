<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\TextFailedEvent;

class Text extends BaseChecker
{
    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new TextFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }

    public function matchResults($pattern, string $input, &$violations)
    {
        if (str_contains($input, $pattern))
        {
            $violations [] = $pattern;
        }
    }
}
