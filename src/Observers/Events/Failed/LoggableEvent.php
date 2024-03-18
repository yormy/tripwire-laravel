<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;

abstract class LoggableEvent implements LoggableEventInterface
{
    use Dispatchable, SerializesModels;

    protected int $score = 10;

    public function __construct(
        protected TriggerEventData $triggerEventData
    ) {
        if ($triggerEventData->attackScore) {
            $this->score = $triggerEventData->attackScore;
        }
    }

    public function getScore(?int $score = null): int
    {
        if ($score) {
            return $score;
        }

        return $this->score;
    }

    public function getViolationText(?string $violation = null): string
    {
        if ($violation) {
            return $violation;
        }

        if (! empty($this->triggerEventData->violations)) {
            return implode(',', $this->triggerEventData->violations);
        }

        return '';
    }

    public function getComment(?string $comment = null): string
    {
        if ($comment) {
            return $comment;
        }

        return $this->triggerEventData->comment ?? '';
    }

    public function getTriggerData(): ?string
    {
        return $this->triggerEventData->triggerData;
    }

    /**
     * @return array<string>
     */
    public function getTriggerRules(): array
    {
        return $this->triggerEventData->triggerRules ?? [];
    }

    public function getTrainingMode(): bool
    {
        return $this->triggerEventData->trainingMode ?? false;
    }

    public function getDebugMode(): bool
    {
        return $this->triggerEventData->debugMode ?? false;
    }

    public function getRequest(): ?Request
    {
        return $this->triggerEventData?->request;
    }
}
