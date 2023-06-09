<?php

namespace Yormy\TripwireLaravel\Traits;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Jobs\AddBlockJob;
use Yormy\TripwireLaravel\Services\UrlTester;

trait TripwireHelpers
{
    abstract protected function attackFound(array $violations): void;

    protected function getAttackScore(): int
    {
        return $this->config->attackScore;
    }

    public function skip($request)
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

    protected function blockIfNeeded()
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($this->request ?? null);
        $userClass = config('tripwire.services.user');

        $userId = 0;
        $userType = '';
        if ($this->request ?? false) {
            $userId = $userClass::getId($this->request);
            $userType = $userClass::getType($this->request);
        }

        AddBlockJob::dispatch(
            ipAddress: $ipAddress,
            userId: $userId,
            userType: $userType,
            withinMinutes: $this->config->punish->withinMinutes,
            thresholdScore: $this->config->punish->score,
            penaltySeconds: $this->config->punish->penaltySeconds,
            trainingMode: $this->config->trainingMode,
        );
    }
}
