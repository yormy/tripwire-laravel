<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Yormy\TripwireLaravel\Observers\Events\Failed\Model404Event;
use Yormy\TripwireLaravel\Observers\Events\Failed\Page404Event;
use Yormy\TripwireLaravel\Observers\Events\Failed\ThrottleHitEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\PageNotFoundEvent;
use Yormy\TripwireLaravel\Tripwires\Exceptions\oldModelMissingWire;

class ExceptionInspector
{
    public static function inspect(Throwable $e, Request $request = null): void
    {
        if ($e instanceof ThrottleRequestsException) {
            event(new ThrottleHitEvent());
        }

        if ($e instanceof ModelNotFoundException) {
        }

        if ($e instanceof NotFoundHttpException) {
            event(new PageNotFoundEvent($request));
        }
    }
}
