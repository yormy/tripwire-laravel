<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RequestSizeFailedEvent extends LoggableEvent
{
    public const CODE = 'REQUEST_OVERSIZE';

    protected int $score = 44;
}
