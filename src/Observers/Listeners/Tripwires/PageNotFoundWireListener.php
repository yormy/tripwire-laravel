<?php

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\Observers\Events\Failed\Page404Event;

class PageNotFoundWireListener extends WireBaseListener
{
    public function __construct() {
        parent::__construct('page404');
    }

    public function isAttack($event): bool
    {

        $violations = [];
        $url = $event->request->fullUrl();

        $violations[] = $url;

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    protected function attackFound(array $violations, string $triggerData = null, array $trigggerRules = null): void
    {
        event(new Page404Event(
            attackScore: $this->getAttackScore(),
            violations: $violations,
            triggerData: $triggerData,
            triggerRules: $trigggerRules
        ));

        $this->blockIfNeeded();
    }
}
