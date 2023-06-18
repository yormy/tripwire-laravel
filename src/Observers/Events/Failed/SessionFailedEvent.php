<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class SessionFailedEvent extends LoggableEvent
{
    const CODE = 'SESSION';

    protected int $score = 20;
}
