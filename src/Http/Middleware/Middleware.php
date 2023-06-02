<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\DataObjects\Config;

abstract class Middleware
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware = strtolower((new \ReflectionClass($this))->getShortName());
        $this->user_id = auth()->id() ?: 0;

        $this->config = new Config($this->middleware);
    }

    protected function getAttackScore(): int
    {
        return $this->config->attackScore;
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
            $checkerResponse = config('tripwire.middleware.' . $checker. '.'. "$configName", false);
            if (is_array($checkerResponse)) {
                $triggerResponse = $checkerResponse;
            }
        }

        return new ConfigResponse($request, $triggerResponse);
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
            if ($configResponse->asContinue()) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return $respond->respondWithJson();
            }

            return $respond->respondWithHtml();
        }
        dd('not tripped');

        return $next($request);
    }

    public function skip($request)
    {
        if ($this->config->isDisabled()) {
            return true;
        }

        if ($this->config->isWhitelist($request)) {
            return true;
        }

        if ($this->config->skipMethod($request)) {
            return true;
        }

        if ($this->config->skipRoute($request)) {
            return true;
        }

        return false;
    }

    public function getPatterns()
    {
        return $this->config->patterns;
    }

    public function isAttack($patterns): bool
    {
        $violations = [];
        foreach ($patterns as $pattern) {
            $this->matchResults($pattern, $this->request->input(), $violations);
        }

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    protected abstract function attackFound(array $violations): void;

    public function matchResults($pattern, $input, &$violations)
    {
        $result = false;

        if ( !is_array($input) && !is_string($input)) {
            return false;
        }

        if ( !is_array($input)) {
            $input = $this->prepareInput($input);

            return preg_match($pattern, $input);
        }

        foreach ($input as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if (is_array($value)) {
                if ($result = $this->matchResults($pattern, $value, $matches)) {
                    $violations[] = $matches[0];
                }
            }

            if ($this->config->skipInput($key)) {
                return true;
            }

            $value = $this->prepareInput($value);

            if ( $result = preg_match($pattern, $value, $matches)) {
                $violations[] = $matches[0];
            }
        }

        return $result;
    }

    public function prepareInput($value)
    {
        return $value;
    }
}
