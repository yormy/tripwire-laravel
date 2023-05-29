<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SwearFailedEvent;

class Swear extends BaseWire
{
    public const NAME = 'SWEARY';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SwearFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    /**
     * @return string[]
     *
     * @psalm-return list{0?: string,...}
     */
    public function getPatterns()
    {
        $patterns = [];

        foreach ($this->config->tripwires() as $wire) {
            $patterns[] = '#\b'.$wire.'\b#i';
        }

        return $patterns;
    }
}
