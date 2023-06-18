<?php

namespace Yormy\TripwireLaravel\Observers\Events\Blocked;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripwireBlockedIpEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        protected readonly string $ipAddress,
    ) {
    }
}
