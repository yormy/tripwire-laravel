<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class DatabaseTablesConfig
{
    public string $tripwireLogs;

    public string $tripwireBlocks;

    private function __construct()
    {
        // disable default constructor
    }

    public static function make(
        string $tripwireLogs,
        string $tripwireBlocks,
    ): self {
        $object = new DatabaseTablesConfig();

        $object->tripwireLogs = $tripwireLogs;
        $object->tripwireBlocks = $tripwireBlocks;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new DatabaseTablesConfig();

        $object->tripwireLogs = $data['tripwire_logs'];
        $object->tripwireBlocks = $data['tripwire_blocks'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'tripwire_logs' => $this->tripwireLogs,
            'tripwire_blocks' => $this->tripwireBlocks,
        ];
    }
}
