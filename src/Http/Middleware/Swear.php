<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Yormy\TripwireLaravel\Observers\Events\SwearFailedEvent;

class Swear  extends Middleware
{
    public function getPatterns()
    {
        $patterns = [];

        if (! $words = $this->config->words) {
            return $patterns;
        }

        foreach ((array) $words as $word) {
            $patterns[] = '#\b' . $word . '\b#i';
        }

        return $patterns;
    }

    protected function attackFound(array $violations): void
    {
        // log
        // take action
        $attackScore = $this->getAttackScore();
        event(new SwearFailedEvent(
            attackScore: $attackScore,
            violations: $violations
        ));

        // dd('attack');
        //event(new AttackDetected($log));
    }
}
