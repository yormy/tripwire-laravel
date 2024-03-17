<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\Model404FailedEvent;

class RouteModelBindingWireListener extends WireBaseListener
{
    public const NAME = 'model404';

    public function __construct()
    {
        parent::__construct('model404');
    }

    public function isAttack(Event | Failed $event): bool
    {
        $violations = [];
        if (in_array($event->class, $this->config->tripwires())) {
            $violations[] = $event->value;
        }

        if (! empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $this->config->attackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $this->config->trainingMode(),
                debugMode: $this->config->debugMode(),
                comments: '',
            );
            $this->attackFound($triggerEventData);
        }

        return ! empty($violations);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new Model404FailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
