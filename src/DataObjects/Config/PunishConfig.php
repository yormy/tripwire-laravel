<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class PunishConfig
{
    public int $score;

    public int $withinMinutes;

    public int $penaltySeconds;

    private function __construct()
    {
        // disable default constructor
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

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'within_minutes' => $this->withinMinutes,
            'penalty_seconds' => $this->penaltySeconds,
        ];
    }
}
