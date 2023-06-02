<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Middleware;
use Yormy\TripwireLaravel\Observers\Events\SwearFailedEvent;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;

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
        $attackScore = $this->getAttackScore();

        event(new SwearFailedEvent(
            attackScore: $attackScore,
            violations: $violations
        ));

        $this->blockIfNeeded();

    }
}
