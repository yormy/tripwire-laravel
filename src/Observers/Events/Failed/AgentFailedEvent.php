<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class AgentFailedEvent extends LoggableEvent
{
    public const CODE = 'AGENT';

    protected int $score = 44;
}
