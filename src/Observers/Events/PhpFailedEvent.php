<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class PhpFailedEvent extends LoggableEvent
{
    const CODE = "PHP";

    protected int $score = 44;
}
