<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\PhpFailedEvent;
use Jenssegers\Agent\Agent;

class Php extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new PhpFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

    public function getPatterns(): array
    {
        $patterns = [];

        foreach ($this->config->tripwires as $wire) {
            $patterns[] = '#' . $wire . '#i';
        }

        return $patterns;
    }
}
