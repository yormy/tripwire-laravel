<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class BotFailedEvent extends LoggableEvent
{
    const CODE = "BOT";

    protected int $score = 44;
}
