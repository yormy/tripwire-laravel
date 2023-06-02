<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\BotFailedEvent;
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

        if ( empty($this->config->crawlers)) {
            return false;
        }

        $crawlers = $this->config->crawlers;

        $attackFound = false;

        if ( !empty($crawlers['allow']) && !in_array(RequestSource::getRobot(), $crawlers['allow'])) {
            $attackFound = true;
        }

        if (!empty($crawlers['block']) && in_array(RequestSource::getRobot(), $crawlers['block'])) {
            $attackFound = true;
        }

        if ($attackFound) {
            $this->attackFound([RequestSource::getRobot()]);
            return true;
        }

        return false;
    }
}
