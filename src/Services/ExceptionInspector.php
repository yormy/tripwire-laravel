<?php

namespace Yormy\TripwireLaravel\Services;

use http\Client\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Yormy\TripwireLaravel\Observers\Events\ThrottleHitEvent;

class ExceptionInspector
{
    public static function inspect(Throwable $e, Request $request = null): void
    {
        if ($e instanceof ThrottleRequestsException) {
            ray('inspector found throttle exception');
            // how to check for when activated, not every hit ?
            event(new ThrottleHitEvent());
        }
    }
}
