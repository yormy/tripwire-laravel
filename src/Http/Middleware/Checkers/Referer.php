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

    protected function isGuardAttack(string $value, array $guards): bool
    {
        if ( !$value) {
            return false;
        }

        if ( empty($this->config->guards)) {
            return false;
        }

        $attackFound = false;

        if ( !empty($guards['allow']) && !in_array($value, $guards['allow'])) {
            $attackFound = true;
        }

        if (!empty($guards['block']) && in_array($value, $guards['block'])) {
            $attackFound = true;
        }

        if ($attackFound) {
            $this->attackFound([$value]);
            return true;
        }

        return false;
    }
}
