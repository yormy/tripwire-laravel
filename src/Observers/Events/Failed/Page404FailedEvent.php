<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class Page404FailedEvent extends LoggableEvent
{
    public const CODE = 'PAGE_404';

    protected int $score = 0;
}
