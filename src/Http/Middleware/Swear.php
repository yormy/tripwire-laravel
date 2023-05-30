<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

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

    protected function attackFound(): void
    {
        //$log = $this->log();

        // dd('attack');
        //event(new AttackDetected($log));
    }
}
