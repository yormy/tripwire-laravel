<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class Model404FailedEvent extends LoggableEvent
{
    const CODE = 'MODEL_404';

    protected int $score = 0;
}
