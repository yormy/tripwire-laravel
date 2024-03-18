<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Interfaces;

interface LoggableEventInterface
{
    public function getScore(?int $score = null): int;

    public function getTriggerData(): ?string;

    /**
     * @return array<string>
     */
    public function getTriggerRules(): array;

    public function getDebugMode(): bool;

    public function getTrainingMode(): bool;

    public function getComment(?string $comment = null): string;

    public function getViolationText(?string $violation = null): string;
}
