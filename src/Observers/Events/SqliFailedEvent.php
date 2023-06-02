<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class SqliFailedEvent extends LoggableEvent
{
    const CODE = "SQLI";

    protected int $score = 44;
}
