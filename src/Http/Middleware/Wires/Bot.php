<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\BotFailedEvent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Bot extends BaseWire
{
    public const NAME = 'bot';

    /**
     * @param array<string> $patterns
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function isAttack(array $patterns): bool
    {
        if (! RequestSource::isRobot()) {
            return false;
        }

        $robot = RequestSource::getRobot();

        return $this->isFilterAttack($robot, $this->config->filters());
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new BotFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
