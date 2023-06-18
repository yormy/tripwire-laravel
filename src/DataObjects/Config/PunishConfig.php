<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class PunishConfig
{
    public int $score;

    public int $withinMinutes;

    public int $penaltySeconds;

    private function __construct()
    {
    }

    public static function make(
        int $score,
        int $withinMinutes,
        int $penaltySeconds
    ): self {
        $object = new PunishConfig();

        $object->score = $score;
        $object->withinMinutes = $withinMinutes;
        $object->penaltySeconds = $penaltySeconds;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new PunishConfig();

        $object->score = $data['score'];
        $object->withinMinutes = $data['within_minutes'];
        $object->penaltySeconds = $data['penalty_seconds'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'within_minutes' => $this->withinMinutes,
            'penalty_seconds' => $this->penaltySeconds,
        ];
    }
}
