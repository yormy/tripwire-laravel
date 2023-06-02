<?php

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class ConfigMiddleware
{
    public bool $enabled;
    public array $methods;

    public array $routes;

    public array $inputs;

    public ConfigPunish $punish;

    public array $words;

    public array $patterns;

    public int $attackScore;

    public function __construct(string $checker)
    {
        $data = config('tripwire.middleware.' . $checker);

        $this->enabled = $data['enabled'] ?? $this->tripwireEnabled();
        $this->methods = $data['methods'];
        $this->routes = $data['routes'];
        $this->inputs = $data['inputs'];
        $this->words = $data['words'];

        $this->patterns = $data['patterns'] ?? [];
        $this->attackScore = $data['attack_score'];

        $this->punish = new ConfigPunish($data['punish']);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function isDisabled(): bool
    {
        return !$this->enabled;
    }

    public function isWhitelist(Request $request): bool
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $whitelisted = config('tripwire.whitelist.ips');

        if (empty($whitelisted)) {
            return false;
        }

        return IpUtils::checkIp($ipAddress, $whitelisted);

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

    public function skipInput(string $key): bool
    {
        if (in_array($key, $this->inputs['except'])) {
            return true;
        }

        if ( !empty($this->inputs['only']) && in_array($key, $this->inputs['only'])) {
            return true;
        }

        return false;
    }

    private function tripwireEnabled(): bool
    {
        return config('tripwire.enabled', true);
    }
}
