<?php

namespace Yormy\TripwireLaravel\Observers\Events;

interface LoggableEvent
{
    /**
     * Returns the display name of the event.
     *
     * @return string
     */
    public function getEventName(): string;

    /**
     * Returns the description of the event.
     *
     * @return string
     */
    public function getEventDescription(): string;
}
