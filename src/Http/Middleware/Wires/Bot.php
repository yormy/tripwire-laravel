<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\BotFailedEvent;
use Jenssegers\Agent\Agent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Bot extends BaseWire
{
    public const NAME = 'bot';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new BotFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        if ( !RequestSource::isRobot()) {
            return false;
        }

        $robot = RequestSource::getRobot();

        return $this->isGuardAttack($robot, $this->config->guards);
    }
}
