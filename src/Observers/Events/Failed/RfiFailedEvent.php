<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RfiFailedEvent extends LoggableEvent
{
    public const CODE = 'RFI';

    protected int $score = 20;
}
