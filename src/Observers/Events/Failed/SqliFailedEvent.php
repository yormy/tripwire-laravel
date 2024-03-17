<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class SqliFailedEvent extends LoggableEvent
{
    public const CODE = 'SQLI';

    protected int $score = 44;
}
