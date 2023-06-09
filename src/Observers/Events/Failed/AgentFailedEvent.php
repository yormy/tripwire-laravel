<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class AgentFailedEvent extends LoggableEvent
{
    const CODE = "AGENT";

    protected int $score = 44;
}
