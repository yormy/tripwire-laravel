<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class PhpFailedEvent extends LoggableEvent
{
    const CODE = "PHP";

    protected int $score = 44;
}
