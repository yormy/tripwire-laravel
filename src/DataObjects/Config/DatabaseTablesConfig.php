<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class DatabaseTablesConfig
{
    public function __construct(
        public string $tripwireLogs,
        public string $tripwireBlocks,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'tripwire_logs' => $this->tripwireLogs,
            'tripwire_blocks' => $this->tripwireBlocks,
        ];
    }
}
