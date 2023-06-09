<?php

namespace Yormy\TripwireLaravel\Observers\Events\Tripwires;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class RouteModelBindingFailedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Request $request,
        public $class,
        public $value,
        public $field = null,
    ) {}

}
