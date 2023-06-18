<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class TextFailedEvent extends LoggableEvent
{
    const CODE = 'TEXT';

    protected int $score = 2;
}
