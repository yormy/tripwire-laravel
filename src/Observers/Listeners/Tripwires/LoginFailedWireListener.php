<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\Observers\Events\Failed\LoginFailed;
use Yormy\TripwireLaravel\Observers\Events\Failed\LoginFailedEvent;

class LoginFailedWireListener extends WireBaseListener
{
    public function __construct() {
        parent::__construct('loginfailed');
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

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new LoginFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }
}
