<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\PhpFailedEvent;

class Php extends BaseWire
{
    public const NAME = 'php';

    public function getPatterns(): array
    {
        $patterns = [];

        foreach ($this->config->tripwires() as $wire) {
            $patterns[] = '#'.$wire.'#i';
        }

        return $patterns;
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new PhpFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
