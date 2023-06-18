<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\RefererFailedEvent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Referer extends BaseWire
{
    public const NAME = 'referer';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new RefererFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    public function isAttack($patterns): bool
    {
        $referer = RequestSource::getReferer();

        return $this->isGuardAttack($referer, $this->config->guards);
    }
}
