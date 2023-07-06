<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class ChecksumFailedEvent extends LoggableEvent
{
    const CODE = 'CHECKSUM';

    protected int $score = 100;
}
