<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class ThrottleHitTrippedEvent extends LoggableEvent
{
    public const CODE = 'THROTTLE';

    protected int $score = 0;
}
