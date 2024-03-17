<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\CustomFailedEvent;

class Custom extends BaseWire
{
    public const NAME = 'custom';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new CustomFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
