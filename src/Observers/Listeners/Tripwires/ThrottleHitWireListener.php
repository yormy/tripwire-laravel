<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\Observers\Events\Failed\ThrottleHitTrippedEvent;

class ThrottleHitWireListener extends WireBaseListener
{
    public function __construct() {
        parent::__construct('throttle');
    }

    public function handle($event): void
    {
        if ($this->config->isDisabled()) {
            return;
        }

        if ($this->isAttack($event)) {
            // respond as attack, events cannot respond
        }
    }

    public function isAttack($event): bool
    {
        $this->attackFound([]);

        return true;
    }

    protected function attackFound(array $violations): void
    {
        event(new ThrottleHitTrippedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }
}
