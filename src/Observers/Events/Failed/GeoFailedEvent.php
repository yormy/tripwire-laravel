<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class GeoFailedEvent extends LoggableEvent
{
    const CODE = "GEO";

    protected int $score = 44;
}
