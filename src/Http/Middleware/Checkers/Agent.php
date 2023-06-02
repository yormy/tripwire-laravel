<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\AgentFailedEvent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Agent extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new AgentFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        $agents = $this->config->agents;
        if (empty($agents)) {
            return false;
        }

        $browsers = $agents['browsers'];
        if($this->isGuardAttack(RequestSource::getBrowser(), $browsers)) {
            return true;
        }

        $platforms = $agents['platforms'];
        if($this->isGuardAttack(RequestSource::getPlatform(), $platforms)) {
            return true;
        }

        $devices = $agents['devices'];
        $properties = $agents['properties'];

        return false;
    }

}
