<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class CheckerGroupConfig
{
    public array $checkers;

    private function __construct()
    {}

    public static function make(array $checkers): self
    {
        $object = new CheckerGroupConfig();

        $object->checkers = $checkers;

        return $object;
    }

    public static function makeFromArray(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        $checkerGroups = [];
        foreach ($data as $name => $checkers) {
            $checkerGroups[$name] = CheckerGroupConfig::make(
                $checkers,
            );

        }

       return $checkerGroups;
    }


    public function toArray(): array
    {
        return $this->checkers;
    }
}
