<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class ThrottleHitEvent extends LoggableEvent
{
    const CODE = "THROTTLE";

    protected int $score = 0;
}
