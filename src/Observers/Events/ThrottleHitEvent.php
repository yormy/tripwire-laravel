<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class ThrottleHitEvent extends LoggableEvent
{
    const CODE = "THROTTLE";

    protected int $score = 0;
}
