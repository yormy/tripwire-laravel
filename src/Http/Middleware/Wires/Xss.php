<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\XssFailedEvent;

class Xss extends BaseWire
{
    public const NAME = 'xss';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new XssFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
