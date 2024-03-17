<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\ChecksumFailedEvent;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

/**
 * Goal:
 * Prevent or frustrate hackers using a proxy to change the request and probe the application
 *
 * Method:
 * The frontend posts data and the page calculates the checksum. The backend first validates this checksum
 * to see if there was any change made by a man in the middle or proxy.
 *
 * Effect:
 * If there is a change made in the middle, this is an clear indication of malicious intent.
 * No real user would do this
 */
class ChecksumValidateWire
{

    use TripwireHelpers;
    public const NAME = 'checksum';

    protected Request $request;

    private WireConfig $config;

    public function __construct()
    {
        $this->config = new WireConfig('checksum');
    }

    /**
     * @throws RequestChecksumFailedException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $this->request = $request;

        if ($this->isAttack($request)) {
            $config = $this->getConfig($request, 'checksum');
            $respond = new ResponseDeterminer($config, $request->url());
            if ($respond->asContinue() || $this->config->trainingMode()) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return $respond->respondWithJson();
            }

            return $respond->respondWithHtml();
        }

        $this->cleanup($request);

        return $next($request);
    }

    public function isAttack($request): bool
    {
        $isAttack = false;
        $this->checkTimestamp($request);

        $postedChecksum = (string) $request->headers->get($this->config->wireDetails()->config['posted']);
        $recalculatedChecksum = (string) $request->get($this->config->wireDetails()->config['serverside_calculated']);

        if (! $postedChecksum && $this->allowEmpytChecksum($request)) {
            return false;
        }

        if ($postedChecksum !== $recalculatedChecksum) {
            $isAttack = true;

            $violations = ['checksum-failed'];
            $triggerEventData = new TriggerEventData(
                attackScore: $this->getAttackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $this->config->trainingMode(),
                debugMode: $this->config->debugMode(),
                comments: '',
            );

            $this->attackFound($triggerEventData);
        }

        return $isAttack;
    }

    protected function cleanup(Request $request): void
    {
        $request->request->remove($this->config->wireDetails()->config['serverside_calculated']);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new ChecksumFailedEvent($triggerEventData));

        $this->blockIfNeeded();

        //throw new RequestChecksumFailedException();
    }

    private function checkTimestamp(Request $request): void
    {
        $timestamp = $request->header($this->config->wireDetails()->config['timestamp']);

        if ($timestamp && Carbon::now()->diffInSeconds(Carbon::parse($timestamp / 1000)) > 30) {
            throw new \RuntimeException('Service blocked! Invalid Timestamp Synchronization');
        }
    }

    private function allowEmpytChecksum(Request $request): bool
    {
        if ($request->isMethod('get')) {
            return true;
        }

        return false;
    }
}
