<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

abstract class BaseListener
{
    public function __construct(protected readonly Request $request)
    {
    }

    abstract public function handle(LoggableEvent $event): void;
}
