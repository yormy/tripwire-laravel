<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

abstract class BaseWire
{
    use TripwireHelpers;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware = static::NAME;
        $this->user_id = auth()->id() ?: 0;

        $this->config = new WireConfig($this->middleware);
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $patterns = $this->getPatterns();
        if ($this->isAttack($patterns)) {
            $config = $this->getConfig($request, $this->middleware);
            $respond = new ResponseDeterminer($config, $request->url());
            if ($respond->asContinue() || $this->config->trainingMode()) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return $respond->respondWithJson();
            }

            return $respond->respondWithHtml();
        }

        return $next($request);
    }

    private function getConfig(Request $request, ?string $wire = null): JsonResponseConfig|HtmlResponseConfig|null
    {
        if ($request->wantsJson()) {
            $config = JsonResponseConfig::makeFromArray(config('tripwire.trigger_response.json'));
            $configChecker = JsonResponseConfig::makeFromArray(config('tripwire_wires.'.$wire.'.trigger_response.json'));

        } else {
            $config = HtmlResponseConfig::makeFromArray(config('tripwire.trigger_response.html'));
            $configChecker = HtmlResponseConfig::makeFromArray(config('tripwire_wires.'.$wire.'.trigger_response.html'));
        }

        if (isset($configChecker)) {
            return $configChecker;
        }

        return $config;
    }

    public function getPatterns()
    {
        return $this->config->tripwires();
    }

    public function isAttack($patterns): bool
    {
        $violations = [];
        $rules = [];
        $triggerData = $this->collectInputs();
        foreach ($patterns as $pattern) {
            $this->matchResults($pattern, $triggerData, $currentViolations);
            if ($currentViolations) {
                $violations[] = $currentViolations[0];
                $rules[] = $pattern;
                break;
            }
        }

        if (! empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $this->getAttackScore(),
                violations: $violations,
                triggerData: $triggerData,
                triggerRules: $rules,
                trainingMode: $this->config->trainingMode(),
                debugMode: $this->config->debugMode(),
                comments: '',
            );

            $this->attackFound($triggerEventData);
        }

        return ! empty($violations);
    }

    private function removeItems(array $original, array $toRemove): array
    {
        $filtered = [];
        foreach ($original as $key => $value) {
            if (! in_array($key, $toRemove)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * convert the inputs of the request to a string that can be scanned by the wire
     * as we do not need to know what key it was or where it came from (input or header or cookie) we can
     * simply make 1 long string and check that with the presence of malicous input anywhere in that string
     */
    private function collectInputs(): string
    {
        $exceptInputs[] = 'remember';
        $config = ConfigBuilder::fromArray(config('tripwire'));

        $exceptInputs = $config->inputIgnore->inputs;
        $exceptCookies = $config->inputIgnore->cookies;
        $exceptHeaders = $config->inputIgnore->header;
        $exceptHeaders[] = 'cookie';

        $inputsGlobalFilter = $this->removeItems($this->request->input(), $exceptInputs);

        $inputsLocalFilter = [];
        foreach ($inputsGlobalFilter as $key => $value) {
            if (! $this->config->skipInput($key)) {
                $inputsLocalFilter[$key] = $this->prepareInput($value);
            }
        }

        $exceptHeaders[] = 'accept-charset';
        $exceptHeaders[] = 'accept-language';
        $exceptHeaders[] = 'accept';
        $cookies = $this->removeItems($this->request->cookie(), $exceptCookies);
        $headers = $this->removeItems($this->request->header(), $exceptHeaders);

        $scannableValues[] = $inputsLocalFilter;
        $scannableValues[] = $cookies;
        $scannableValues[] = $headers;

        // add full url without domain name to be able to find other stuff
        $domain = $this->request->root();

        $fullUrl = $this->request->fullUrl();

        $fullUrlWithoutDomain = str_replace($domain, '', $fullUrl);
        $scannableValues[] = $fullUrlWithoutDomain;
        $scannableValues[] = urldecode($fullUrlWithoutDomain);

        $stringed = '';
        $this->convertValuesToString($scannableValues, $stringed);

        return $stringed;
    }

    private function convertValuesToString(array $data, string &$string): void
    {
        foreach ($data as $field => $value) {
            if (is_array($value)) {
                $this->convertValuesToString($value, $string);
            } else {
                if ($value) {
                    $string .= '-'.$value;
                }
            }
        }
    }

    /**
     * @return false|int
     *
     * @psalm-return 0|1|false
     */
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
        if (! $value) {
            return false;
        }

        if (empty($guards)) {
            return false;
        }

        $attackFound = false;

        if (! empty($guards['allow']) && ! in_array($value, $guards['allow'])) {
            $attackFound = true;
        }

        if (! empty($guards['block']) && in_array($value, $guards['block'])) {
            $attackFound = true;
        }

        if ($attackFound) {
            $this->attackFound([$value]);

            return true;
        }

        return false;
    }

    public function prepareInput($value): string
    {
        return $value;
    }
}