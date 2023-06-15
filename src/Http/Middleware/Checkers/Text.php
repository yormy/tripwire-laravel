<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\TextFailedEvent;

class Text extends BaseChecker
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
