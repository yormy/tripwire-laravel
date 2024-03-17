<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RequestChecksumFailedEvent extends LoggableEvent
{
    public const CODE = 'REQUEST_CHECKSUM_FAILED';

    protected int $score = 0;
}
