<?php

namespace Yormy\TripwireLaravel\Observers;

use Illuminate\Events\Dispatcher;
use Yormy\TripwireLaravel\Observers\Events\General\RouteModelBindingFailedEvent;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;
use Yormy\TripwireLaravel\Observers\Listeners\LogEvent;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\RouteModelBindingFailedListener;

class TripwireSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        // Listen to all events that implement the interface
        $events->listen(
            LoggableEventInterface::class,
            LogEvent::class
        );

        $events->listen(
            RouteModelBindingFailedEvent::class,
            RouteModelBindingFailedListener::class
        );
    }
}
