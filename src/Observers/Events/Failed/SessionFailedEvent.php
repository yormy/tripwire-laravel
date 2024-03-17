<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class SessionFailedEvent extends LoggableEvent
{
    public const CODE = 'SESSION';

    protected int $score = 20;
}
