<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class RefererFailedEvent extends LoggableEvent
{
    const CODE = "REFERER";

    protected int $score = 20;
}
