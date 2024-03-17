<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class PhpFailedEvent extends LoggableEvent
{
    public const CODE = 'PHP';

    protected int $score = 44;
}
