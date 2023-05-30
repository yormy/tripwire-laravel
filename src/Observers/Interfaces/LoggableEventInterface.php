<?php

namespace Yormy\TripwireLaravel\Observers\Interfaces;

interface LoggableEventInterface
{
    public function getScore(int $score = null): int;
}
