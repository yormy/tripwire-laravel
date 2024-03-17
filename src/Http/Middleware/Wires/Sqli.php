<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SqliFailedEvent;

class Sqli extends BaseWire
{
    public const NAME = 'sqli';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SqliFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
