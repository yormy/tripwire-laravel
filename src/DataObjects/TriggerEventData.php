<?php

namespace Yormy\TripwireLaravel\DataObjects;

class TriggerEventData
{
    public function __construct(
        public int $attackScore,
        public array $violations,
        public string $triggerData,
        public array $triggerRules,
        public bool $trainingMode,
        public string $comments,
    ) {
    }
}
