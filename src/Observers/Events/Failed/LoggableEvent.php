<?php

namespace Yormy\TripwireLaravel\Observers\Events\Failed;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;

abstract class LoggableEvent implements LoggableEventInterface
{
    use Dispatchable, SerializesModels;

    protected int $score = 10;

    public function __construct(
        protected ?int $attackScore = null,
        protected ?array $violations = null,
        protected ?string $comment = null
    ) {
        if ($attackScore) {
            $this->score = $attackScore;
        }
    }

    public function getScore(?int $score = null): int
    {
        if ($score) {
            return $score;
        }

        return $this->score;
    }

    public function getViolationText(string $violation = null): string
    {
        if ($violation) {
            return $violation;
        }

        if (!empty($this->violations)) {
            return implode(',', $this->violations);
        }

        return '';
    }

    public function getComment(string $comment = null): string
    {
        if ($comment) {
            return $comment;
        }

        return $this->comment ?? '';
    }
}