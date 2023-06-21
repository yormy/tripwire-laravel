<?php

namespace Yormy\TripwireLaravel\Services;

class Regex
{
    const FILLER = '[\s|\x00]';

    const FILLERSEMI = '[;|/]';
    const QUOTE = '["|\'|&quot;|&apos;]';

    const LT = '[<|&lt;|%3c|¼|%BC]';

    const GT = '[>|%3e|¾|%BE]';

    public static function forbidden(array $signatures, string $delim = '#'): string
    {
        return $delim.'('.self::or($signatures).')'.$delim.'iUu';
    }

    public static function or(array $signatures): string
    {
        $clean = self::injectFillers($signatures);

        return implode('|', $clean);
    }

    public static function makeWhitespaceSafe(string $signature): string
    {
        return str_replace(' ', self::FILLER. '*', $signature);
    }

    public static function injectFillers(array $signatures): array
    {
        $clean = array_map(function ($signature) {
            return self::makeWhitespaceSafe($signature);
        }, $signatures);

        return $clean;
    }
}
