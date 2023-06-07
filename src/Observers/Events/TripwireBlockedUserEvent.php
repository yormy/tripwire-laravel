<?php

namespace Yormy\TripwireLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripwireBlockedUserEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        protected readonly int $userId,
        protected readonly string $userType,
    ) {}

}
