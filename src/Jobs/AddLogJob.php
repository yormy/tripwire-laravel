<?php

namespace Yormy\TripwireLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class AddLogJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private $event,
        private array $meta
    )
    { }

    public function handle()
    {
        $logRepository = new LogRepository();
        $logRepository->add($this->event, $this->meta);
    }
}
