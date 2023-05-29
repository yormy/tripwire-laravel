<?php

namespace Yormy\TripwireLaravel\Events;

class ModelsEncrypted
{
    public function __construct(
        public readonly string $model,
        public readonly int $count,
        public readonly float $durationInSeconds
    ) {

    }
}
