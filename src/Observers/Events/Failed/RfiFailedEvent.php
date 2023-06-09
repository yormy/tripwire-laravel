<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class RfiFailedEvent extends LoggableEvent
{
    const CODE = "RFI";

    protected int $score = 20;
}
