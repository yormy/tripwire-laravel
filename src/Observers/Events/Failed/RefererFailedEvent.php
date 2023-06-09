<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class RefererFailedEvent extends LoggableEvent
{
    const CODE = "REFERER";

    protected int $score = 20;
}
