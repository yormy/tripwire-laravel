<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class XssFailedEvent extends LoggableEvent
{
    public const CODE = 'XSS';

    protected int $score = 44;
}
