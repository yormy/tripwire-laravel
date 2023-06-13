<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class PunishConfig
{
    public int $score;

    public int $withinMinues;

    public int $penaltySeconds;

    private function __construct()
    {}

    public static function make(
        int $score,
        int $withinMinues,
        int $penaltySeconds
    ): self
    {
        $object = new PunishConfig();

        $object->score = $score;
        $object->withinMinues = $withinMinues;
        $object->penaltySeconds = $penaltySeconds;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new PunishConfig();

        $object->score = $data['score'];
        $object->withinMinues = $data['within_minutes'];
        $object->penaltySeconds = $data['penalty_seconds'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'within_minutes' => $this->withinMinues,
            'penalty_seconds' => $this->penaltySeconds,
        ];
    }
}
