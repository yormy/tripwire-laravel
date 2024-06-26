<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class Model404FailedEvent extends LoggableEvent
{
    public const CODE = 'MODEL_404';

    protected int $score = 0;
}
