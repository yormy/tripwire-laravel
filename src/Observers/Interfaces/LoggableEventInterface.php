<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Interfaces;

interface LoggableEventInterface
{
    public function getScore(?int $score = null): int;
}
