<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class SwearFailedEvent extends LoggableEvent
{
    public const CODE = 'SWEAR';

    protected int $score = 20;
}
