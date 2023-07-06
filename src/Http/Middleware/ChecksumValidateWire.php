<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use App\Exceptions\Exceptions\RequestChecksumFailedException;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;

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

    private WireDetailsConfig $checksumDetails;

    public function __construct()
    {
        $this->checksumDetails = WireDetailsConfig::makeFromArray(config('tripwire_wires.checksum'));
    }
    /**
     * @return mixed
     *
     * @throws RequestChecksumFailedException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->checkTimestamp($request);

        $postedChecksum = (string) $request->headers->get($this->checksumDetails->config['posted']);
        $recalculatedChecksum = (string) $request->get($this->checksumDetails->config['serverside_calculated']);

        if (! $postedChecksum) {
            if (! $this->allowEmpytChecksum($request)) {
                //throw new RequestChecksumFailedException();
            }

            // ... checksum missing from post
            // allow missing for now
            return $next($request);
        }

        if ($postedChecksum !== $recalculatedChecksum) {
            throw new RequestChecksumFailedException();
        }

        $this->cleanup($request);

        return $next($request);
    }

    /**
     * @return void
     */
    private function checkTimestamp(Request $request)
    {

        $timestamp = $request->header($this->checksumDetails->config['timestamp']);

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
        $request->request->remove($this->checksumDetails->config['serverside_calculated']);
    }
}
