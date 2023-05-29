<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RfiFailedEvent extends LoggableEvent
{
    const CODE = 'RFI';

    protected int $score = 20;
}
