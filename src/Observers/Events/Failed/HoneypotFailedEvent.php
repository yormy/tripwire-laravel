<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class HoneypotFailedEvent extends LoggableEvent
{
    public const CODE = 'HONEYPOT';

    protected int $score = 10;
}
