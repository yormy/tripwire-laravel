<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class ChecksumFailedEvent extends LoggableEvent
{
    public const CODE = 'CHECKSUM';

    protected int $score = 100;
}
