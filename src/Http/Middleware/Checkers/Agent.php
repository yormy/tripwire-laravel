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

        $violations = [];

        $browsers = $agents['browsers'];
        if($this->isGuardAttack(RequestSource::getBrowser(), $browsers)) {
            $violations[] = RequestSource::getBrowser();
        }

        $platforms = $agents['platforms'];
        if($this->isGuardAttack(RequestSource::getPlatform(), $platforms)) {
            $violations[] = RequestSource::getPlatform();
        }

        $devicesBlocked = $agents['devices']['block'];
        if($blocked = $this->isDeviceBlocked($devicesBlocked)) {
            $violations[] = $blocked;
        }

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    private function isDeviceBlocked(array $devices): ?string
    {
        if (RequestSource::isMobile()) {
            if (in_array('MOBILE', $devices)) {
                return 'MOBILE';
            }
        }

        if (RequestSource::isTablet()) {
            if (in_array('TABLET', $devices)) {
                return 'TABLET';
            }
        }

        if (RequestSource::isDesktop()) {
            if (in_array('DESKTOP', $devices)) {
                return 'DESKTOP';
            }
        }

        return null;
    }

}
