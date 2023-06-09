<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class LfiFailedEvent extends LoggableEvent
{
    const CODE = "LFI";

    protected int $score = 20;
}
