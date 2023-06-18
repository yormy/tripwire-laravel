<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\LoginFailedEvent;

class LoginFailedWireListener extends WireBaseListener
{
    public function __construct()
    {
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

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new LoginFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
