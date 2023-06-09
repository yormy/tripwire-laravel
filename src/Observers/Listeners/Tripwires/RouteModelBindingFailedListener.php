<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\Observers\Events\Failed\Model404Event;

class RouteModelBindingFailedListener extends BaseListener
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

    protected function attackFound(array $violations): void
    {
        event(new Model404Event(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }
}
