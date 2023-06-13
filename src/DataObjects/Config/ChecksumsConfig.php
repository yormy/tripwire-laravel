<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ChecksumsConfig
{
    public function __construct(
        public string $posted,
        public string $timestamp,
        public string $serversideCalculated,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'posted' => $this->posted,
            'timestamp' => $this->timestamp,
            'serverside_calculated' => $this->serversideCalculated,
        ];
    }
}
