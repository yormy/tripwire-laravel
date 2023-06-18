<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SessionFailedEvent;

class Session extends BaseWire
{
    public const NAME = 'session';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SessionFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

}
