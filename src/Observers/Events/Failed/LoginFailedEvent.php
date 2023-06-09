<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class LoginFailedEvent extends LoggableEvent
{
    const CODE = "LOGIN_FAILED";

    protected int $score = 0;
}
