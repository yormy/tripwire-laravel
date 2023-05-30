<?php

namespace Yormy\TripwireLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;

abstract class LoggableEvent implements LoggableEventInterface
{
    use Dispatchable, SerializesModels;

    protected int $score = 10;

    public function getScore(int $score = null): int
    {
        if ($score) {
            return $score;
        }

        return $this->score;
    }

    public function getComment(string $comment = null): string
    {
        if ($comment) {
            return $comment;
        }

        return '';
    }
}
