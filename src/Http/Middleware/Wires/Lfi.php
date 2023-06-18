<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\LfiFailedEvent;

class Lfi extends BaseWire
{
    public const NAME = 'lfi';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new LfiFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
