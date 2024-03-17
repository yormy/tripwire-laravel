<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Contracts\Support\Arrayable;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireGroupConfig;

class ConfigBuilderWires implements Arrayable
{
    /**
     * @var array<string> $wires
     */
    public array $wires;

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        $data = [];

        if (isset($this->wires)) {
            foreach ($this->wires as $name => $wire) {
                $data[$name] = $wire->toArray();
            }
        }

        return $data;
    }

    /**
     * @param array<string> $data
     */
    public static function fromArray(array $data): self
    {
        $config = new self();

        $config->wireGroups = WireGroupConfig::makeFromArray($data['wire_groups'] ?? null);

        return $config;
    }

    public function addWireDetails(
        string $name,
        WireDetailsConfig $wire,
    ): self {
        $this->wires[$name] = $wire;

        return $this;
    }

    public static function make(): self
    {
        return new self();
    }
}
