<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class RequestChecksumFailedEvent extends LoggableEvent
{
    const CODE = "REQUEST_CHECKSUM_FAILED";

    protected int $score = 0;


}
