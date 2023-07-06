<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use App\Exceptions\Exceptions\RequestChecksumFailedException;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\ChecksumFailedEvent;
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
    public const NAME = 'checksum';

    use TripwireHelpers;
    private WireConfig $config;

    protected Request $request;

    public function __construct()
    {
        $this->config = new WireConfig('checksum');
    }

    /**
     * @return mixed
     *
     * @throws RequestChecksumFailedException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $this->request = $request;

        $this->checkTimestamp($request);

        $postedChecksum = (string) $request->headers->get($this->config->wireDetails()->config['posted']); // ???????
        $recalculatedChecksum = (string) $request->get($this->config->wireDetails()->config['serverside_calculated']);

        if (! $postedChecksum) {
            if (! $this->allowEmpytChecksum($request)) {
                //throw new RequestChecksumFailedException();
            }

            // ... checksum missing from post
            // allow missing for now
            return $next($request);
        }

        if ($postedChecksum !== $recalculatedChecksum) {
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

        $this->cleanup($request);

        return $next($request);
    }

    /**
     * @return void
     */
    private function checkTimestamp(Request $request)
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

    protected function cleanup(Request $request): void
    {
        $request->request->remove($this->config->wireDetails()->config['serverside_calculated']);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new ChecksumFailedEvent($triggerEventData));

        $this->blockIfNeeded();

        // TODO: Implement attackFound() method.
        throw new RequestChecksumFailedException();
    }
}
