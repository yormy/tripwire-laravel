<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class CheckAllowBlock
{
    /**
     * @param  array<array<string>>  $filters
     */
    public static function shouldBlock(string $value, array $filters, bool $default = false): bool
    {
        if (! $value) {
            return false;
        }

        if (empty($filters)) {
            return false;
        }

        if (empty($filters['allow'])) {
            throw new \Exception("Empty Allow not valid, to allow all specify ['*']");
        }

        if (in_array($value, $filters['allow'])) {
            return false;
        }

        if (empty($filters['block'])) {
            return false;
        }

        if (! empty($filters['block'])) {
            if (in_array($value, $filters['block'])) {
                return true;
            }
        }

        return $default;
    }
}
