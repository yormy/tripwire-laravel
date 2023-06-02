<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class BotFailedEvent extends LoggableEvent
{
    const CODE = "BOT";

    protected int $score = 44;
}
