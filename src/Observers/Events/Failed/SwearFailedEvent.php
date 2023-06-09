<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class SwearFailedEvent extends LoggableEvent
{
    const CODE = "SWEAR";

    protected int $score = 20;
}
