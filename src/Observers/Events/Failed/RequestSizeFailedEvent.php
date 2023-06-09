<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class RequestSizeFailedEvent extends LoggableEvent
{
    const CODE = "REQUEST_OVERSIZE";

    protected int $score = 44;
}
