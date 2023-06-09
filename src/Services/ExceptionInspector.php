<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Yormy\TripwireLaravel\Observers\Events\Model404Event;
use Yormy\TripwireLaravel\Observers\Events\Page404Event;
use Yormy\TripwireLaravel\Observers\Events\ThrottleHitEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yormy\TripwireLaravel\Tripwires\Exceptions\oldModelMissingWire;

class ExceptionInspector
{
    public static function inspect(Throwable $e, Request $request = null): void
    {
        if ($e instanceof ThrottleRequestsException) {
            ray('throttle exception');
            // how to check for when activated, not every hit ?
            event(new ThrottleHitEvent());
        }

        if ($e instanceof ModelNotFoundException) {
        }

        if ($e instanceof NotFoundHttpException) {
            $fullUrl = $request->fullUrl();
            $event404 = (new Page404Event(violations: [$fullUrl]));
            event($event404);
        }
    }
}
