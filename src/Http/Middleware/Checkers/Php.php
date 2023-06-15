<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\PhpFailedEvent;
use Jenssegers\Agent\Agent;

class Php extends BaseChecker
{
    public const NAME = 'php';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new PhpFailedEvent($triggerEventData));

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
