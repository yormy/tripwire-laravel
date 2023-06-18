<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\TextFailedEvent;

class Text extends BaseWire
{
    public const NAME = 'text';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new TextFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    public function matchResults($pattern, string $input, &$violations)
    {
        if (str_contains($input, $pattern))
        {
            $violations [] = $pattern;
        }
    }
}
