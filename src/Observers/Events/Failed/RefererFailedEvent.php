<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RefererFailedEvent extends LoggableEvent
{
    public const CODE = 'REFERER';

    protected int $score = 20;
}
