<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\TextFailedEvent;

class Text extends BaseWire
{
    public const NAME = 'text';

    /**
     * @param array<string> $violations
     */
    public function matchResults(string $pattern, string $input, array | null &$violations): false|int
    {
        if (str_contains($input, $pattern)) {
            $violations[] = $pattern;
            return 1;
        }

        return 0;
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new TextFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
