<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class XssFailedEvent extends LoggableEvent
{
    const CODE = "XSS";

    protected int $score = 44;
}
