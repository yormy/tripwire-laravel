<?php

namespace Yormy\TripwireLaravel\Services;

class Regex
{
    const FILLER = '(\s|\x00)';

    const QUOTE = '("|\')';

    public static function forbidden(array $signatures, string $delim = '#'): string
    {
        return $delim.'('.self::or($signatures).')'.$delim.'iUu';
    }

    public static function or(array $signatures): string
    {
        $clean = self::clean($signatures);

        return implode('|', $clean);
    }

    public static function makeWhitespaceSafe(string $signature): string
    {
        return str_replace(' ', self::FILLER. '*', $signature);
    }

    public static function clean(array $signatures): array
    {
        $clean = array_map(function ($signature) {
            return self::makeWhitespaceSafe($signature);
        }, $signatures);

        return $clean;
    }
}
