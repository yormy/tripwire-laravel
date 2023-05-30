<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class TestFailedEvent extends LoggableEvent
{
    const CODE = "test";

    protected int $score = 20;

    public function getComment(): string
    {
        return 'tja;';
    }

}
