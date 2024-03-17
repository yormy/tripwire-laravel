<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class GeoFailedEvent extends LoggableEvent
{
    public const CODE = 'GEO';

    protected int $score = 44;
}
