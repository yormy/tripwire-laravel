<?php

namespace Yormy\TripwireLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestFailedEvent implements LoggableEvent
{
    use Dispatchable, SerializesModels;

    private string $code = "REQUEST_CHECKSUM_FAILED";

    private string $severity = "severe";

    public function getCode()
    {
        return $this->code;
    }

    public function getSeverity()
    {
        return $this->severity;
    }

    public function getEventName(): string
    {
        return 'Created Application Member';
    }

    public function getEventDescription(): string
    {
        return 'Fired whenever a new Application Member is added to your Application.';
    }
}
