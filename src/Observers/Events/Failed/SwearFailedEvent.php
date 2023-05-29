<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class SwearFailedEvent extends LoggableEvent
{
    const CODE = 'SWEAR';

    protected int $score = 20;
}
