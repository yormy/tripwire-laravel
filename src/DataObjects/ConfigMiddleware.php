<?php

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Yormy\TripwireLaravel\Services\UrlTester;

class ConfigMiddleware
{
    public bool $enabled;
    public array $methods;

    public array $urls;

    public array $inputs;

    public array $guards;

    public array $custom;

    public ConfigPunish $punish;

    public array $words;

    public array $patterns;

    public int $attackScore;

    public bool $trainingMode = false;

    public function __construct(string $checker)
    {
        $data = config('tripwire.middleware.' . $checker);

        $this->enabled = $data['enabled'] ?? $this->tripwireEnabled();
        $this->methods = $data['methods'];
        $this->urls = $data['urls'] ?? [];
        $this->inputs = $data['inputs'] ?? [];
        $this->words = $data['words'] ?? [];

        $this->patterns = $data['patterns'] ?? [];
        $this->attackScore = $data['attack_score'];

        if ($punishData = $data['punish'] ?? false) {
            $this->punish = new ConfigPunish($punishData);
        } else {
            $this->punish = new ConfigPunish(config('tripwire.punish'));
        }

        $this->guards = $data['guards'] ?? [];
        $this->custom = $data['custom'] ?? [];

        if (isset($data['training_mode'])) {
            $this->trainingMode = $data['training_mode'];
        } else {
            $this->trainingMode = config('tripwire.training_mode', false);
        }
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

        if (in_array('all', $this->methods) || in_array('*', $this->methods)) {
            return false;
        }

        return !in_array(strtolower($request->method()), $this->methods);
    }


    public function skipUrl(Request $request): bool
    {
        return UrlTester::skipUrl($request, $this->urls);
    }

    public function skipInput(string $key): bool
    {
        if (empty($this->inputs)) {
            return false;
        }

        if (in_array($key, $this->inputs['except'])) {
            return true;
        }

        if ( !empty($this->inputs['only']) && !in_array($key, $this->inputs['only'])) {
            return true;
        }

        return false;
    }

    private function tripwireEnabled(): bool
    {
        return config('tripwire.enabled', true);
    }
}
