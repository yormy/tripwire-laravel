<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class GeoFailedEvent extends LoggableEvent
{
    const CODE = "GEO";

    protected int $score = 44;
}
