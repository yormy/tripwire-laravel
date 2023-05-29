<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class HoneypotsConfig
{
    public int $attackScore;

    public array $mustBeMissingOrFalse;

    private function __construct()
    {
    }

    public static function make(int $attackScore, array $mustBeMissingOrFalse): self
    {
        $object = new HoneypotsConfig();
        $object->attackScore = $attackScore;
        $object->mustBeMissingOrFalse = $mustBeMissingOrFalse;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {

        if (! $data) {
            return null;
        }

        $object = new HoneypotsConfig();
        $object->attackScore = $data['attack_score'];
        $object->mustBeMissingOrFalse = $data['must_be_missing_or_false'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'attack_score' => $this->attackScore,
            'must_be_missing_or_false' => $this->mustBeMissingOrFalse,
        ];
    }
}
