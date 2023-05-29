<?php

namespace Yormy\TripwireLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestChecksumFailedEvent
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
}
