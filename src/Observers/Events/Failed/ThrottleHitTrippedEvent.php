<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class ThrottleHitTrippedEvent extends LoggableEvent
{
    const CODE = 'THROTTLE';

    protected int $score = 0;
}
