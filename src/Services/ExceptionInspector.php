<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\PageNotFoundEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\ThrottleHitEvent;
use Yormy\TripwireLaravel\Tripwires\Exceptions\oldModelMissingWire;

class ExceptionInspector
{
    public static function inspect(Throwable $e, Request $request = null): void
    {
        if ($e instanceof ThrottleRequestsException) {
            event(new ThrottleHitEvent($request));
        }

        if ($e instanceof ModelNotFoundException) {
        }

        if ($e instanceof NotFoundHttpException) {
            event(new PageNotFoundEvent($request));
        }
    }
}
