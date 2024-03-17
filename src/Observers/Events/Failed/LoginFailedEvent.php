<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class LoginFailedEvent extends LoggableEvent
{
    public const CODE = 'LOGIN_FAILED';

    protected int $score = 0;
}
