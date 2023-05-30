<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Illuminate\Http\Request;

class Config
{
    public bool $enabled;
    public array $methods;

    public array $routes;

    public array $inputs;

    public array $autoBlocks;

    public array $words;

    public array $patterns;

    public function __construct(string $checker)
    {
        $data = config('tripwire.middleware.' . $checker);

        $this->enabled = $data['enabled'] ?? $this->tripwireEnabled();
        $this->methods = $data['methods'];
        $this->routes = $data['routes'];
        $this->inputs = $data['inputs'];
        $this->autoBlocks = $data['auto_block'];
        $this->words = $data['words'];

        $this->patterns = $data['patterns'] ?? [];

    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function isDisabled(): bool
    {
        return !$this->enabled;
    }

    public function skipMethod(Request $request): bool
    {
        if ( !$this->methods) {
            return true;
        }

        if (in_array('all', $this->methods)) {
            return false;
        }

        return !in_array(strtolower($request->method()), $this->methods);
    }


    public function skipRoute(Request $request): bool
    {
        if ( !$this->routes) {
            return false;
        }

        foreach ($this->routes['except'] as $ex) {
            if (! $request->is($ex)) {
                continue;
            }

            return true;
        }

        foreach ($this->routes['only'] as $on) {
            if ($request->is($on)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function tripwireEnabled(): bool
    {
        return config('tripwire.enabled', true);
    }
}
