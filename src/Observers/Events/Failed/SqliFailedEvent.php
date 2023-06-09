<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class SqliFailedEvent extends LoggableEvent
{
    const CODE = "SQLI";

    protected int $score = 44;
}
