<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class TextFailedEvent extends LoggableEvent
{
    const CODE = "TEXT";

    protected int $score = 2;
}
