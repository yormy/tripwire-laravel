<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\Model404FailedEvent;

class RouteModelBindingWireListener extends WireBaseListener
{
    public function __construct() {
        parent::__construct('model404');
    }

    public function isAttack($event): bool
    {
        $violations = [];
        if (in_array($event->class, $this->config->tripwires)) {
            $violations[] = $event->value;
        };

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new Model404FailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
