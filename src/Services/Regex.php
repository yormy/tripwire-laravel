<?php

namespace Yormy\TripwireLaravel\Services;

class Regex
{
    public static function forbidden(array $signatures, string $delim = '#'): string
    {
        return $delim.'('.self::or($signatures).')'.$delim.'iUu';
    }

    public static function or(array $signatures): string
    {
        return implode('|', $signatures);
    }
}
