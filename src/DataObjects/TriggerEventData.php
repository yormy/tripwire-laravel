<?php

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TriggerEventData
{
    public function __construct(
        public int $attackScore,
        public array $violations,
        public string $triggerData,
        public array $triggerRules,
        public bool $trainingMode,
        public string $comments,
    ) {}
}
