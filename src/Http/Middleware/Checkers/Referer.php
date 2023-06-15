<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\RefererFailedEvent;
use Jenssegers\Agent\Agent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Referer extends BaseChecker
{
    public const NAME = 'referer';

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new RefererFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        $referer = RequestSource::getReferer();

        return $this->isGuardAttack($referer, $this->config->guards);
    }
}
