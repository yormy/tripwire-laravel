<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Tripwires;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class RouteModelBindingFailedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Request $request,
        public string $class,
        public string $value,
        public ?string $field = null,
    ) {
    }
}
