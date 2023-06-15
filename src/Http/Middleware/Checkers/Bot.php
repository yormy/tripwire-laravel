<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\BotFailedEvent;
use Jenssegers\Agent\Agent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Bot extends BaseChecker
{
    public const NAME = 'bot';

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new BotFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
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
