<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ModelsConfig
{
    public function __construct(
        public string $log,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'log' => $this->log,
        ];
    }
}
