<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class BotFailedEvent extends LoggableEvent
{
    public const CODE = 'BOT';

    protected int $score = 44;
}
