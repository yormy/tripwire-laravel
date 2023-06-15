<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SqliFailedEvent;

class Sqli extends BaseChecker
{
    public const NAME = 'sqli';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SqliFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

}
