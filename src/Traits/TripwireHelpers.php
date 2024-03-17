<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Traits;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Services\BlockIfNeeded;
use Yormy\TripwireLaravel\Services\UrlTester;

trait TripwireHelpers
{
    public function skip(Request $request): bool
    {
        if ($this->config->isDisabled()) {
            return true;
        }

        if (UrlTester::skipUrl($request, config('tripwire.urls'))) {
            return true;
        }

        if ($this->config->isWhitelist($request)) {
            return true;
        }

        if ($this->config->skipMethod($request)) {
            return true;
        }

        if ($this->config->skipUrl($request)) {
            return true;
        }

        return false;
    }
    abstract protected function attackFound(TriggerEventData $triggerEventData): void;

    protected function getAttackScore(): int
    {
        return $this->config->attackScore();
    }

    protected function blockIfNeeded(): void
    {
        BlockIfNeeded::run($this->request, $this->config->punish(), $this->config->trainingMode());
    }

    private function getConfig(Request $request, ?string $wire = null): JsonResponseConfig|HtmlResponseConfig|null
    {
        if ($request->wantsJson()) {
            $config = JsonResponseConfig::makeFromArray(config('tripwire.reject_response.json'));
            $configChecker = JsonResponseConfig::makeFromArray(config('tripwire_wires.'.$wire.'.reject_response.json'));
        } else {
            $config = HtmlResponseConfig::makeFromArray(config('tripwire.reject_response.html'));
            $configChecker = HtmlResponseConfig::makeFromArray(config('tripwire_wires.'.$wire.'.reject_response.html'));
        }

        if (isset($configChecker)) {
            return $configChecker;
        }

        return $config;
    }
}
