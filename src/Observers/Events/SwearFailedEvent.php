<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class SwearFailedEvent extends LoggableEvent
{
    const CODE = "SWEAR";

    protected int $score = 20;
}
