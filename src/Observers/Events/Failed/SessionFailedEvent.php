<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class SessionFailedEvent extends LoggableEvent
{
    const CODE = "SESSION";

    protected int $score = 20;
}
