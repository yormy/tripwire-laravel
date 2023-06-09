<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\ConfigMiddleware;
use Yormy\TripwireLaravel\Jobs\AddBlockJob;
use Yormy\TripwireLaravel\Services\UrlTester;

abstract class BaseListener
{
    protected ConfigMiddleware $config;

    protected Request $request;

    abstract protected function attackFound(array $violations): void;

    public function __construct(string $tripwire)
    {
        $this->config = new ConfigMiddleware($tripwire);
    }

    public function handle($event): void
    {
        $this->request = $event->request;
        if ($this->skip($this->request)) {
            return;
        }

        if ($this->isAttack($event)) {
            // respond as attack, events cannot respond
        }
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
        $ipAddress = $ipAddressClass::get($this->request);

        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($this->request);
        $userType = $userClass::getType($this->request);

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

    protected function getAttackScore(): int
    {
        return $this->config->attackScore;
    }
}
