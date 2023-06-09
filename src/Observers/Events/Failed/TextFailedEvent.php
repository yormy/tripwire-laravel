<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class TextFailedEvent extends LoggableEvent
{
    const CODE = "TEXT";

    protected int $score = 2;
}
