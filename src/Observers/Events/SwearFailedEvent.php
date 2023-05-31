<?php

namespace Yormy\TripwireLaravel\Observers\Events;

class SwearFailedEvent extends LoggableEvent
{
    const CODE = "SWEAR";

    protected int $score = 20;

    public function __construct(
        int $attackScore,
        protected ?array $violations = null,
        protected ?string $comment = null
    ) {
        $this->score = $attackScore;
    }

    public function getComment(string $comment = null): string
    {
        if ($this->comment) {
            return $this->comment;
        }

        return parent::getComment($comment);
    }

    public function getViolationText(string $violation = null): string
    {
        if (!empty($this->violations)) {
            return implode(',', $this->violations);
        }

        return parent::getViolationText($violation);
    }

}
