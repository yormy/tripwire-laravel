<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\Services\CheckOnlyExcept;
use Yormy\TripwireLaravel\Services\UrlTester;

class WireConfig
{
    private ConfigBuilder $defaultConfig;

    private WireDetailsConfig $wireConfig;

    public function __construct(string $wire)
    {
        $this->defaultConfig = ConfigBuilder::fromArray(config('tripwire')); // @phpstan-ignore-line
        $this->wireConfig = WireDetailsConfig::makeFromArray(config('tripwire_wires.'.$wire)); // @phpstan-ignore-line
    }

    public function attackScore(): int
    {
        if (isset($this->wireConfig->attackScore)) {
            return $this->wireConfig->attackScore;
        }

        return 0;
    }

    public function isEnabled(): bool
    {
        if (! $this->defaultConfig->enabled) {
            return false;
        }

        if (isset($this->wireConfig->enabled)) {
            return $this->wireConfig->enabled;
        }

        return true;
    }

    public function trainingMode(): bool
    {
        $trainingMode = $this->defaultConfig->trainingMode ?? false;

        if (isset($this->wireConfig->trainingMode)) {
            $trainingMode = $this->wireConfig->trainingMode;
        }

        return $trainingMode;
    }

    /**
     * @return array<string>
     */
    public function whitelistedTokens(): array
    {
        return $this->wireConfig->whitelistedTokens;
    }

    public function debugMode(): bool
    {
        return $this->defaultConfig->debugMode ?? false;
    }

    public function isDisabled(): bool
    {
        return ! $this->isEnabled();
    }

    /**
     * @return array<string>
     */
    public function methods(): array
    {
        if (isset($this->wireConfig->methods)) {
            return $this->wireConfig->methods;
        }

        return [];
    }

    /**
     * @return array<string, array<string>>
     */
    public function urls(): array
    {
        if (isset($this->wireConfig->urls)) {
            return $this->wireConfig->urls->toArray();
        }

        return $this->defaultConfig->urls?->toArray() ?? [];
    }

    public function punish(): PunishConfig
    {
        if (isset($this->wireConfig->punish)) {
            return $this->wireConfig->punish;
        }

        return $this->defaultConfig->punish;
    }

    /**
     * @return array<string>
     */
    public function inputs(): array
    {
        if (isset($this->wireConfig->inputs)) {
            return $this->wireConfig->inputs->toArray();
        }

        return [];
    }

    /**
     * @return array<string>
     */
    public function tripwires(): array
    {
        if (isset($this->wireConfig->tripwires)) {
            return $this->wireConfig->tripwires;
        }

        return [];
    }

    /**
     * @return array<string>
     */
    public function filters(): array
    {
        if (isset($this->wireConfig->filters)) {
            return $this->wireConfig->filters;
        }

        return [];
    }

    public function skipMethod(Request $request): bool
    {
        if ($request->method() === 'OPTIONS') {
            return true;
        }

        if (! $this->methods()) {
            return true;
        }

        if (in_array('all', $this->methods()) || in_array('*', $this->methods())) {
            return false;
        }

        return ! in_array(strtolower($request->method()), $this->methods());
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
            if (IpUtils::checkIp($ipAddress, $ip)) {
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

        return CheckOnlyExcept::needsProcessing($key, $this->inputs);
    }

    public function wireDetails(): WireDetailsConfig
    {
        return $this->wireConfig;
    }
}
