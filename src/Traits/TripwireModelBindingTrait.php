<?php

namespace Yormy\TripwireLaravel\Traits;

use Yormy\TripwireLaravel\Observers\Events\Tripwires\RouteModelBindingFailedEvent;
use Illuminate\Http\Request;

trait TripwireModelBindingTrait
{
    public function resolveRouteBinding($value, $field = null)
    {
        $model = parent::resolveRouteBinding($value, $field);
        if (!$model) {
            event(new RouteModelBindingFailedEvent(request(), get_class($this), $value, $field));
        }

        return $model;
    }
}
