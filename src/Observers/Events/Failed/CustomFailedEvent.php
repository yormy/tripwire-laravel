<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class CustomFailedEvent extends LoggableEvent
{
    const CODE = 'custom';

    protected int $score = 1;
}
