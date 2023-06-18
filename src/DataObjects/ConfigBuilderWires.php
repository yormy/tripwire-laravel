<?php
namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Contracts\Support\Arrayable;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireGroupConfig;

class ConfigBuilderWires implements Arrayable
{
    public array $wires;

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

    public static function fromArray(array $data)
    {
        $config = new self();

        $config->wireGroups = WireGroupConfig::makeFromArray($data['wire_groups'] ?? null);

        return $config;
    }

    public function addWireDetails(
        string            $name,
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
