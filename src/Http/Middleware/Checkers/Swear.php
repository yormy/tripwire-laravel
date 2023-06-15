<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SwearFailedEvent;

class Swear extends BaseChecker
{
    public const NAME = 'SWEARY';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SwearFailedEvent($triggerEventData));

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
