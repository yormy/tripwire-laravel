<?php

namespace Yormy\TripwireLaravel\Services;

class CheckAllowBlock
{
    public static function shouldBlock(string $value, array $guards, bool $default = false): bool
    {
        if (! $value) {
            return false;
        }

        if (empty($guards)) {
            return false;
        }

        if (empty($guards['allow'])) {
            throw new \Exception("Empty Allow not valid, to allow all specify ['*']");
        }

        if (in_array($value, $guards['allow'])) {
            return false;
        }

        if (empty($guards['block'])) {
            return false;
        }

        if (! empty($guards['block'])) {
            if (in_array($value, $guards['block'])) {
                return true;
            }
        }

        return $default;
    }
}
