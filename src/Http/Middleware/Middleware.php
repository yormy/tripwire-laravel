<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

// use Akaunting\Firewall\Events\AttackDetected;
// use Akaunting\Firewall\Traits\Helper;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

abstract class Middleware
{
    use Helper;

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

    public function handle(Request $request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $patterns = $this->getPatterns();
        if ($this->isAttack($patterns)) {
            $configResponse = new ConfigResponse($request, $this->middleware);

            if ($configResponse->asContinue()) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return $this->respondJson($configResponse);
            }

            return $this->respondHtml($configResponse);
        }
        //dd('not tripped');

        return $next($request);
    }

    public function respondJson(ConfigResponse $configResponse, array $data = [])
    {
        $configResponse->asException();

        if ($response = $configResponse->asJson()) {
            return $response;
        }

        if ($response = $configResponse->asGeneralMessage()) {
            return $response;
        }

        $configResponse->asGeneralAbort();
    }

    public function respondHtml(ConfigResponse $configResponse, array $data = [])
    {
        $configResponse->asException();

        if ($response = $configResponse->asView($data)) {
            return $response;
        }

        if ($response = $configResponse->asRedirect($data)) {
            return $response;
        }

        if ($response = $configResponse->asGeneralMessage()) {
            return $response;
        }

        $configResponse->asGeneralAbort();
    }


    public function skip($request)
    {
        if ($this->config->isDisabled()) {
            return true;
        }

        if ($this->isWhitelist()) {
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

        return empty($violations);
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

            if ( !$this->isInput($key)) {
                continue;
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
