<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\TextFailedEvent;

class Text extends BaseChecker
{
    protected function attackFound(array $violations): void
    {
        event(new TextFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

    public function getPatterns()
    {
        $patterns = [];

        foreach ($this->config->texts as $text) {
            $patterns[] = "#$text#i";
        }

        return $patterns;
    }

}
