<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\RefererFailedEvent;
use Jenssegers\Agent\Agent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Referer extends BaseChecker
{

    protected function attackFound(array $violations): void
    {
        event(new RefererFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        $referer = RequestSource::getReferer();

        return $this->isGuardAttack($referer, $this->config->guards);
    }
}
