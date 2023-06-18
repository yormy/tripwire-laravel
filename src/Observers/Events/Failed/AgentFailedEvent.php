<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

class AgentFailedEvent extends LoggableEvent
{
    const CODE = 'AGENT';

    protected int $score = 44;
}
