<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Yormy\TripwireLaravel\Jobs\AddLogJob;
use Yormy\TripwireLaravel\Services\LogRequestService;

class LogEvent extends BaseListener
{
    public function handle($event)
    {
        $meta = LogRequestService::getMeta($this->request);

        AddLogJob::dispatch(
            event: $event,
            meta: $meta,
        );
    }
}
