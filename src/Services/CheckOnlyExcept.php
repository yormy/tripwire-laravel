<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Yormy\TripwireLaravel\DataObjects\Config\OnlyExceptConfig;

class CheckOnlyExcept
{
    public static function needsProcessing(string $value, OnlyExceptConfig $config): bool
    {
        if (in_array($value, $config->except)) {
            return false;
        }

        if (in_array($value, $config->only)) {
            return true;
        }

        if (is_array($config->only) && ! empty($config->only)) {
            return false;
        }

        return true;
    }
}
