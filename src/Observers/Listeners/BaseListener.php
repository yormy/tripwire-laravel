<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Illuminate\Http\Request;

abstract class BaseListener
{
    public function __construct(protected readonly Request $request)
    { }

    public abstract function handle($event);
}
