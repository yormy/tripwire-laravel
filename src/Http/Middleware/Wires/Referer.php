<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\RefererFailedEvent;
use Yormy\TripwireLaravel\Services\RequestSource;

class Referer extends BaseWire
{
    public const NAME = 'referer';

    /**
     * @param  array<string>  $patterns
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function isAttack(array $patterns): bool
    {
        $referer = RequestSource::getReferer();

        return $this->isFilterAttack($referer, $this->config->filters());
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new RefererFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
