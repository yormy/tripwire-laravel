<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class Page404Event extends LoggableEvent
{
    const CODE = "PAGE_404";

    protected int $score = 0;
}
