<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\LoggableEvent;
use Yormy\TripwireLaravel\Observers\Events\Failed\ThrottleHitTrippedEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\ThrottleHitEvent;

class ThrottleHitWireListener extends WireBaseListener
{
    public const NAME = 'throttle';

    public function __construct()
    {
        parent::__construct('throttle');
    }

    public function handle(Failed|LoggableEvent|ThrottleHitEvent $event): void
    {
        $this->request = request();

        if ($this->config->isDisabled()) {
            return;
        }

        $this->isAttack($event);
        //        if ($isAttack) {
        //            //abort(406); // respond as attack, events cannot respond
        //        }
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function isAttack(Event|Failed|ThrottleHitEvent $event): bool
    {
        $violations = ['throttle_hit'];
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

        return true;
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new ThrottleHitTrippedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
