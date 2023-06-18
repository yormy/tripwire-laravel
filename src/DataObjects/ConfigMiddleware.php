<?php

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\Services\UrlTester;

class ConfigMiddleware
{
    private ConfigBuilder $defaultConfig;
    private CheckerDetailsConfig $checkerConfig;

    public function __construct(string $checker)
    {
        $this->defaultConfig = ConfigBuilder::fromArray(config('tripwire'));
        $this->checkerConfig = CheckerDetailsConfig::makeFromArray(config('tripwire_wires.' . $checker));
    }

    public function attackScore(): int
    {
        if (isset($this->checkerConfig->attackScore)) {
            return $this->checkerConfig->attackScore;
        }

        return 0;
    }

    public function isEnabled(): bool
    {
        if (!$this->defaultConfig->enabled) {
            return false;
        }

        if (isset($this->checkerConfig->enabled)) {
            return $this->checkerConfig->enabled;
        }

        return true;
    }

    public function trainingMode(): bool
    {
        $trainingMode = $this->defaultConfig->trainingMode ?? false;

        if (isset($this->checkerConfig->trainingMode)) {
            $trainingMode = $this->checkerConfig->trainingMode;
        }

        return $trainingMode;
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function methods(): array
    {
        if (isset($this->checkerConfig->methods)) {
            return $this->checkerConfig->methods;
        }

        return [];
    }

    public function urls(): array
    {
        if (isset($this->checkerConfig->urls)) {
            return $this->checkerConfig->urls->toArray();
        }

        return $this->defaultConfig->urls?->toArray() ?? [];
    }

    public function punish(): PunishConfig
    {
        if (isset($this->checkerConfig->punish)) {
            return  $this->checkerConfig->punish;
        }

        return $this->defaultConfig->punish;
    }

    public function inputs(): array
    {
        if (isset($this->checkerConfig->inputs)) {
            return  $this->checkerConfig->inputs->toArray();
        }

        return [];
    }

    public function tripwires(): array
    {
        if (isset($this->checkerConfig->tripwires)) {
            return  $this->checkerConfig->tripwires;
        }

        return [];
    }

    public function guards(): array
    {
        if (isset($this->checkerConfig->guards)) {
            return  $this->checkerConfig->guards;
        }

        return [];
    }

    public function skipMethod(Request $request): bool
    {
        if ( !$this->methods()) {
            return true;
        }

        if (in_array('all', $this->methods()) || in_array('*', $this->methods())) {
            return false;
        }

        return !in_array(strtolower($request->method()), $this->methods());
    }

    public function isWhitelist(Request $request): bool
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($request);

        $whitelistedIps = $this->defaultConfig->whitelist->toArray();

        if (empty($whitelistedIps)) {
            return false;
        }

        $isWhitelisted = false;
        foreach ($whitelistedIps as $ip) {
            if (IpUtils::checkIp($ipAddress, $ip))
            {
                $isWhitelisted = true;
            }
        }
        return $isWhitelisted;

    }

    public function skipUrl(Request $request): bool
    {
        return UrlTester::skipUrl($request, $this->urls());
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

}
