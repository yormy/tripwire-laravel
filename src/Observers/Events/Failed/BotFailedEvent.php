<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class BotFailedEvent extends LoggableEvent
{
    const CODE = "BOT";

    protected int $score = 44;
}
