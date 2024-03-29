<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class WireGroupConfig
{
    /**
     * @var array<string>
     */
    public array $wires;

    private function __construct()
    {
        // disable default constructor
    }

    /**
     * @param  array<string>  $wires
     */
    public static function make(array $wires): self
    {
        $object = new WireGroupConfig();

        $object->wires = $wires;

        return $object;
    }

    /**
     * @param  array<string>|null  $data
     *
     * @return array<string>|null
     */
    public static function makeFromArray(?array $data): ?array
    {
        if (! $data) {
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

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return $this->wires;
    }
}
