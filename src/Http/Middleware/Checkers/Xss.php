<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\XssFailedEvent;

class Xss extends BaseChecker
{
    public const NAME = 'xss';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new XssFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

}
