<?php

namespace Yormy\TripwireLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripwireBlockedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $ipAddress,
        public readonly int $userId,
        public readonly string $userType,
        public readonly string $browserFingerprint,
    ) {}
}
