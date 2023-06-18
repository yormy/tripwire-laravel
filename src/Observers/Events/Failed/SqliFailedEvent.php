<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class SqliFailedEvent extends LoggableEvent
{
    const CODE = 'SQLI';

    protected int $score = 44;
}
