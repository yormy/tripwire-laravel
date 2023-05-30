<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class RequestChecksumFailedEvent extends LoggableEvent
{
    const CODE = "REQUEST_CHECKSUM_FAILED";

    protected int $score = 0;


}
