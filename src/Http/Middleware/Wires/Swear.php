<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SwearFailedEvent;

class Swear extends BaseWire
{
    public const NAME = 'SWEARY';

    /**
     * @return array<string>
     */
    public function getPatterns(): array
    {
        $patterns = [];

        foreach ($this->config->tripwires() as $wire) {
            $patterns[] = '#\b'.$wire.'\b#i';
        }

        return $patterns;
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SwearFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
