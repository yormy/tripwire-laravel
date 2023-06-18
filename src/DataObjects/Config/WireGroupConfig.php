<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class WireGroupConfig
{
    public array $wires;

    private function __construct()
    {}

    public static function make(array $wires): self
    {
        $object = new WireGroupConfig();

        $object->wires = $wires;

        return $object;
    }

    public static function makeFromArray(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        $wireGroups = [];
        foreach ($data as $name => $wires) {
            $wireGroups[$name] = WireGroupConfig::make(
                $wires,
            );

        }

       return $wireGroups;
    }


    public function toArray(): array
    {
        return $this->wires;
    }
}
