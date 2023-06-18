<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class HoneypotFailedEvent extends LoggableEvent
{
    const CODE = "HONEYPOT";

    protected int $score = 10;
}
