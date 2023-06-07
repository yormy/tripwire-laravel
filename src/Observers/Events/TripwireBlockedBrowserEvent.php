<?php

namespace Yormy\TripwireLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripwireBlockedBrowserEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        protected readonly string $browserFingerprint,
    ) {}

}
