<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class LfiFailedEvent extends LoggableEvent
{
    const CODE = 'LFI';

    protected int $score = 20;
}
