<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\SwearFailedEvent;

class Swear extends BaseChecker
{
    public const NAME = 'SWEARY';

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new SwearFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }

    public function getPatterns()
    {
        $patterns = [];

        foreach ($this->config->tripwires as $wire) {
            $patterns[] = '#\b' . $wire . '\b#i';
        }

        return $patterns;
    }


}
