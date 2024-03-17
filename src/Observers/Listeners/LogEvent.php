<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Yormy\TripwireLaravel\Jobs\AddLogJob;
use Yormy\TripwireLaravel\Services\LogRequestService;

class LogEvent extends BaseListener
{
    public function handle($event): void
    {
        $meta = LogRequestService::getMeta($this->request);

        AddLogJob::dispatch(
            event: $event,
            meta: $meta,
        );
    }
}
