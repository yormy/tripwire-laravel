<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;

class TriggerEventData
{
    /**
     * @param  array<string>  $violations
     * @param  array<string>  $triggerRules
     */
    public function __construct(
        public int $attackScore,
        public array $violations,
        public string $triggerData,
        public array $triggerRules,
        public bool $trainingMode,
        public bool $debugMode,
        public string $comments,
        public ?Request $request = null,
    ) {
    }
}
