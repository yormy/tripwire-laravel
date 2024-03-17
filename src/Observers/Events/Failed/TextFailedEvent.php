<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class TextFailedEvent extends LoggableEvent
{
    public const CODE = 'TEXT';

    protected int $score = 2;
}
