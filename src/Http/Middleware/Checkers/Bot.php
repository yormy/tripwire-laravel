<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\BotFailedEvent;
use Jenssegers\Agent\Agent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Bot extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new BotFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        if ( !RequestSource::isRobot()) {
            return false;
        }

        $robot = RequestSource::getRobot();

        return $this->isGuardAttack($robot, $this->config->guards);
    }
}
