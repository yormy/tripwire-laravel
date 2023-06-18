<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RefererFailedEvent extends LoggableEvent
{
    const CODE = 'REFERER';

    protected int $score = 20;
}
