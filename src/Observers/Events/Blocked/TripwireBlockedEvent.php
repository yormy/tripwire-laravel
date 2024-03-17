<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Blocked;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripwireBlockedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $ipAddress,
        public readonly ?int $userId,
        public readonly ?string $userType,
        public readonly ?string $browserFingerprint,
    ) {
    }
}
