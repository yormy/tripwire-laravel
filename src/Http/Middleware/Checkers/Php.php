<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\PhpFailedEvent;
use Jenssegers\Agent\Agent;

class Php extends BaseChecker
{
    public const NAME = 'php';

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new PhpFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
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
