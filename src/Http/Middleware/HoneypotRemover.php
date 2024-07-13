<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\HoneypotFailedEvent;
use Yormy\TripwireLaravel\Services\HoneypotService;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

/**
 * Goal:
 * Trigger when hackers try to edit the request in a way to guess hidden parameters.
 *
 * Effect:
 * When a honeypot is filled or changed the application will immediately notice malicious intent
 * No real user would do this
 */
class HoneypotRemover
{
    public const NAME = 'honeypot';

    protected WireConfig $config;

    public function handle(Request $request, Closure $next): mixed
    {
        $this->config = new WireConfig(static::NAME);

        $this->cleanup($request, $this->config);

        return $next($request);
    }

    protected function cleanup(Request $request, WireConfig $wireConfig): void
    {
        foreach ($wireConfig->tripwires() as $field) {
            $request->request->remove($field);
        }
    }
}
