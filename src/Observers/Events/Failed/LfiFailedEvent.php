<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class LfiFailedEvent extends LoggableEvent
{
    public const CODE = 'LFI';

    protected int $score = 20;
}
