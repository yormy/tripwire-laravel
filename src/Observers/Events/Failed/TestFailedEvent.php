<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class TestFailedEvent extends LoggableEvent
{
    const CODE = "test";

    protected int $score = 20;

    public function getComment(string $comment = null): string
    {
        return 'tja;';
    }

}
