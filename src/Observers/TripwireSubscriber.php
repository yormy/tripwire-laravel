<?php

namespace Yormy\TripwireLaravel\Observers;

use Illuminate\Events\Dispatcher;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\PageNotFoundEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\RouteModelBindingFailedEvent;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;
use Yormy\TripwireLaravel\Observers\Listeners\LogEvent;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\PageNotFoundListenerWire;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\RouteModelBindingFailedListenerWire;

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
            RouteModelBindingFailedListenerWire::class
        );

        $events->listen(
            PageNotFoundEvent::class,
            PageNotFoundListenerWire::class
        );
    }
}
