<?php

namespace Yormy\TripwireLaravel\Traits;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Services\BlockIfNeeded;
use Yormy\TripwireLaravel\Services\UrlTester;

trait TripwireHelpers
{
    abstract protected function attackFound(TriggerEventData $triggerEventData): void;

    protected function getAttackScore(): int
    {
        return $this->config->attackScore();
    }

    public function skip($request): bool
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

    protected function blockIfNeeded(): void
    {
        BlockIfNeeded::run($this->request, $this->config->punish(), $this->config->trainingMode());
    }
}
