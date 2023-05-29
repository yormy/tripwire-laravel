<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Services\Honeypot;

/**
 * Goal:
 * Trigger when hackers try to edit the request in a way to guess hidden parameters.
 *
 * Effect:
 * When a honeypot is filled or changed the application will immediately notice malicious intent
 * No real user would do this
 */
class HoneypotsCheck
{
    /**
     * @return mixed
     *
     * @throws \Mexion\BedrockCore\Exceptions\ExceptionResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $honeypotsMustBeFalseOrMissing = (array)config('tripwire.honeypots.must_be_missing_or_false');
        Honeypot::checkFalseValues($request, $honeypotsMustBeFalseOrMissing);

        $this->cleanup($request);

        return $next($request);
    }

    protected function cleanup(Request $request): void
    {
        $honeypotsMustBeFalseOrMissing = config('tripwire.honeypots.must_be_missing_or_false');
        foreach ($honeypotsMustBeFalseOrMissing as $field) {
            $request->request->remove($field);
        }
    }
}
