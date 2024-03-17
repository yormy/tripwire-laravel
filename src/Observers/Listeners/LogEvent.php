<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Illuminate\Auth\Events\Failed;
use Yormy\TripwireLaravel\Jobs\AddLogJob;
use Yormy\TripwireLaravel\Services\LogRequestService;
use Illuminate\Support\Facades\Event;
use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;

class LogEvent extends BaseListener
{
    public function handle(Failed | LoggableEvent $event): void
    {
        $meta = LogRequestService::getMeta($this->request);

        AddLogJob::dispatch(
            event: $event,
            meta: $meta,
        );
    }
}
