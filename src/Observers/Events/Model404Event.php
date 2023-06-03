<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class Model404Event extends LoggableEvent
{
    const CODE = "MODEL_404";

    protected int $score = 0;
}
