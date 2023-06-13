<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class HoneypotsConfig
{
    public array $mustBeMissingOrFalse;

    private function __construct()
    {}

    public static function make(array $mustBeMissingOrFalse): self
    {
        $object = new HoneypotsConfig();

        $object->mustBeMissingOrFalse = $mustBeMissingOrFalse;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new HoneypotsConfig();

        $object->mustBeMissingOrFalse = $data['must_be_missing_or_false'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'must_be_missing_or_false' => $this->mustBeMissingOrFalse,
        ];
    }
}
