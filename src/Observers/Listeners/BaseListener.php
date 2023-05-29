<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Illuminate\Http\Request;

abstract class BaseListener
{
    public function __construct(protected readonly Request $request)
    {
    }

    abstract public function handle($event);
}
