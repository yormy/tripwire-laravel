<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ModelsConfig
{
    public string $log;

    public string $block;

    private function __construct()
    {
    }

    public static function make(
        string $log,
        string $block,
    ): self {
        $object = new ModelsConfig();

        $object->log = $log;
        $object->block = $block;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new ModelsConfig();

        $object->log = $data['log'];
        $object->block = $data['block'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'log' => $this->log,
            'block' => $this->block,
        ];
    }
}
