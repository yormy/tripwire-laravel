<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class CustomFailedEvent extends LoggableEvent
{
    public const CODE = 'custom';

    protected int $score = 1;
}
