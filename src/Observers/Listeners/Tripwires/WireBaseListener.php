<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\ConfigMiddleware;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

abstract class WireBaseListener
{
    use TripwireHelpers;

    protected ConfigMiddleware $config;

    protected Request $request;

    public function __construct(string $tripwire)
    {
        $this->config = new ConfigMiddleware($tripwire);
    }

    public function handle($event): void
    {
        $this->request = $event->request;
        if ($this->skip($this->request)) {
            return;
        }

        if ($this->isAttack($event)) {
            // respond as attack, events cannot respond
        }
    }
}
