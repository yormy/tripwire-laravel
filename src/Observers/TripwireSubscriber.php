<?php

namespace Yormy\TripwireLaravel\Observers;

use Illuminate\Events\Dispatcher;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;
use Yormy\TripwireLaravel\Observers\Listeners\LogEvent;

class TripwireSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        // Listen to all events that implement the interface
        $events->listen(
            LoggableEventInterface::class,
            LogEvent::class
        );

    }
}
