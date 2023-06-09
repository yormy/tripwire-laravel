<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Yormy\TripwireLaravel\Jobs\AddBlockJob;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\DataObjects\ConfigMiddleware;
use Yormy\TripwireLaravel\Services\UrlTester;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

abstract class BaseChecker
{
    use TripwireHelpers;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware = strtolower((new \ReflectionClass($this))->getShortName());
        $this->user_id = auth()->id() ?: 0;

        $this->config = new ConfigMiddleware($this->middleware);
    }


    public function handle(Request $request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $patterns = $this->getPatterns();
        if ($this->isAttack($patterns)) {
            $configResponse = $this->getConfig($request, $this->middleware);
            $respond = new ResponseDeterminer($configResponse);
            if ($configResponse->asContinue() || $this->config->trainingMode) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return $respond->respondWithJson();
            }

            return $respond->respondWithHtml();
        }

        return $next($request);
    }

    private function getConfig(Request $request, ?string $checker = null): ConfigResponse
    {
        $configName = 'trigger_response';
        if ($request->wantsJson()) {
            $configName .='.json';
        } else {
            $configName .='.html';
        }

        $generalResponse = config("tripwire.$configName");
        $triggerResponse = $generalResponse;
        if ($checker) {
            $checkerResponse = config('tripwire_wires.' . $checker. '.'. "$configName", false);
            if (is_array($checkerResponse)) {
                $triggerResponse = $checkerResponse;
            }
        }

        return new ConfigResponse($triggerResponse);
    }

    public function getPatterns()
    {
        return $this->config->tripwires;
    }

    public function isAttack($patterns): bool
    {
        $violations = [];
        foreach ($patterns as $pattern) {
            $this->matchResults($pattern, $this->collectInputs(), $violations);
        }

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    private function removeItems(array $original, array $toRemove): array
    {
        $filtered = [];
        foreach ($original as $key => $value) {
            if (!in_array($key, $toRemove)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }
    /**
     * convert the inputs of the request to a string that can be scanned by the checkers
     * as we do not need to know what key it was or where it came from (input or header or cookie) we can
     * simply make 1 long string and check that with the presence of malicous input anywhere in that string
     */
    private function collectInputs(): string
    {
        $exceptInputs[] = 'remember';
        $exceptInputs = config('tripwire.ignore.inputs', []);
        $exceptCookies = config('tripwire.ignore.cookie', []);
        $exceptHeaders = config('tripwire.ignore.header', []);;
        $exceptHeaders[] = 'cookie';

        $inputsGlobalFilter = $this->removeItems($this->request->input(), $exceptInputs);

        $inputsLocalFilter = [];
        foreach ($inputsGlobalFilter as $key => $value) {
            if (!$this->config->skipInput($key)) {
                $inputsLocalFilter[$key] = $this->prepareInput($value);
            }
        }

        $cookies = $this->removeItems($this->request->cookie(), $exceptCookies);
        $headers = $this->removeItems($this->request->header(), $exceptHeaders);

        $scannableValues[] = $inputsLocalFilter;
        $scannableValues[] = $cookies;
        $scannableValues[] = $headers;

        $fullUrl = $this->request->fullUrl();
        $scannableValues[] = $fullUrl;
        $scannableValues[] = urldecode($fullUrl);

        return json_encode($scannableValues);
    }

    public function matchResults($pattern, string $input, &$violations)
    {
        return preg_match($pattern, $input, $violations);
    }

    protected function matchAdditional($value): ?string
    {
        return null;
    }

    protected function isGuardAttack(string $value, array $guards): bool
    {
        if ( !$value) {
            return false;
        }

        if ( empty($guards)) {
            return false;
        }

        $attackFound = false;

        if ( !empty($guards['allow']) && !in_array($value, $guards['allow'])) {
            $attackFound = true;
        }

        if (!empty($guards['block']) && in_array($value, $guards['block'])) {
            $attackFound = true;
        }

        if ($attackFound) {
            $this->attackFound([$value]);
            return true;
        }

        return false;
    }

    public function prepareInput($value)
    {
        return $value;
    }
}
