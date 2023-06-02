<?php

namespace Yormy\TripwireLaravel\DataObjects;

use http\Exception\InvalidArgumentException;

class ConfigPunish
{
    public int $score;

    public int $withinMinutes = 10;

    public int $penaltySeconds = 5;

    public function __construct(array $data) {
        $this->score = $data['score'] ?? 0;

        if ($withinMinutes = $data['within_minutes'] ?? false) {
            $this->withinMinutes = $withinMinutes;
        }

        // note this will log increase on every violation that leads to a block
        // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
        if ($penaltySeconds = $data['penalty_seconds'] ?? false) {
            if ($penaltySeconds === 1) {
                throw new InvalidArgumentException('Penalty seconds cannot be 1, this will invalidate log penaly settings and all penalties will be just 1 sec');
            }

            $this->penaltySeconds = $penaltySeconds;
        }
    }
}
