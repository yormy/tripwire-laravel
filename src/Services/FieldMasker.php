<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class FieldMasker
{
    public static function run(array $inputs)
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
