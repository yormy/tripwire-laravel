<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class Model404Event extends LoggableEvent
{
    const CODE = "MODEL_404";

    protected int $score = 0;
}
