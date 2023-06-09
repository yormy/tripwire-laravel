<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class XssFailedEvent extends LoggableEvent
{
    const CODE = "XSS";

    protected int $score = 44;
}
