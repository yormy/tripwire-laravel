<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\ThrottleHitTrippedEvent;

class ThrottleHitWireListener extends WireBaseListener
{
    public const NAME = 'throttle';

    public function __construct()
    {
        parent::__construct('throttle');
    }

    public function handle($event): void
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
    public function isAttack($event): bool
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
