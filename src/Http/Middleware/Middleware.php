<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

// use Akaunting\Firewall\Events\AttackDetected;
// use Akaunting\Firewall\Traits\Helper;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class Middleware
{
    use Helper;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware = strtolower((new \ReflectionClass($this))->getShortName());
        // dd($this->middleware);
        $this->user_id = auth()->id() ?: 0;

        $this->config = new Config($this->middleware);
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }
        dd('kk');

        if ($this->check($this->getPatterns())) {
            return $this->respond(config('firewall.responses.block'));
        }

        return $next($request);
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
        return config('firewall.middleware.' . $this->middleware . '.patterns', []);
    }

    public function check($patterns)
    {
        $log = null;

        foreach ($patterns as $pattern) {
            if (! $match = $this->match($pattern, $this->request->input())) {
                continue;
            }

            $log = $this->log();

            event(new AttackDetected($log));

            break;
        }

        if ($log) {
            return true;
        }

        return false;
    }

    public function match($pattern, $input)
    {
        $result = false;

        if (! is_array($input) && !is_string($input)) {
            return false;
        }

        if (! is_array($input)) {
            $input = $this->prepareInput($input);

            return preg_match($pattern, $input);
        }

        foreach ($input as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if (is_array($value)) {
                if (!$result = $this->match($pattern, $value)) {
                    continue;
                }

                break;
            }

            if (! $this->isInput($key)) {
                continue;
            }

            $value = $this->prepareInput($value);

            if (! $result = preg_match($pattern, $value)) {
                continue;
            }

            break;
        }

        return $result;
    }

    public function prepareInput($value)
    {
        return $value;
    }

    public function respond($response, $data = [])
    {
        if ($response['code'] == 200) {
            return '';
        }

        if ($view = $response['view']) {
            return Response::view($view, $data, $response['code']);
        }

        if ($redirect = $response['redirect']) {
            if (($this->middleware == 'ip') && $this->request->is($redirect)) {
                abort($response['code'], trans('firewall::responses.block.message'));
            }

            return Redirect::to($redirect);
        }

        if ($response['abort']) {
            abort($response['code'], trans('firewall::responses.block.message'));
        }

        if (array_key_exists('exception', $response)) {
            if ($exception = $response['exception']) {
                throw new $exception();
            }
        }

        return Response::make(trans('firewall::responses.block.message'), $response['code']);
    }
}
