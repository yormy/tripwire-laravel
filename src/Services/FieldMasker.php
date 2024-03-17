<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class FieldMasker
{
    /**
     * @param array<array|string> $inputs
     */
    public static function run(array $inputs): array
    {
        foreach ($inputs as $key => $input) {
            if (is_array($input)) {
                return self::run($input);
            }

            if (in_array($key, config('tripwire.log.remove', []))) {
                $inputs[$key] = '****';
            }
        }

        return $inputs;
    }
}
