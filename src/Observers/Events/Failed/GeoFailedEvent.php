<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class GeoFailedEvent extends LoggableEvent
{
    const CODE = 'GEO';

    protected int $score = 44;
}
