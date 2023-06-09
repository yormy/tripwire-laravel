<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\SwearFailedEvent;

class Swear extends BaseChecker
{
    protected function attackFound(array $violations): void
    {
        event(new SwearFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
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
