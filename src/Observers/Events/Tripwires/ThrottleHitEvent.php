<?php

namespace Yormy\TripwireLaravel\Observers\Events\Tripwires;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class ThrottleHitEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Request $request,
    ) {
    }
}
