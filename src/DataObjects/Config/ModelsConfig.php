<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ModelsConfig
{
    public string $log;

    private function __construct()
    {
    }

    public static function make(
        string $log,
    ): self {
        $object = new ModelsConfig();

        $object->log = $log;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new ModelsConfig();

        $object->log = $data['log'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'log' => $this->log,
        ];
    }
}
