<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\SessionFailedEvent;

class Session extends BaseChecker
{
    public const NAME = 'session';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new SessionFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

}
