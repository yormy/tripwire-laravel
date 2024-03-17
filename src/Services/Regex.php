<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class Regex
{
    public const FILLER = '[\+\s\x00]';

    public const FILLERSEMI = '[;/]';

    public const QUOTE = '["\'&quot;&apos;]';

    public const LT = '([<¼]|(&lt;)|%3C|%BC)';

    public const GT = '([>¾])|%3E|%BE';

    /**
     * @param array<string> $signatures
     */
    public static function forbidden(array $signatures, string $delim = '#'): string
    {
        return $delim.'('.self::or($signatures).')'.$delim.'iUu';
    }

    /**
     * @param array<string> $signatures
     */
    public static function or(array $signatures): string
    {
        $clean = self::injectFillers($signatures);

        return implode('|', $clean);
    }

    public static function makeWhitespaceSafe(string $signature): string
    {
        return str_replace(' ', self::FILLER.'*', $signature);
    }

    /**
     * @param array<string> $signatures
     * @return array<string>
     */
    public static function injectFillers(array $signatures): array
    {
        return array_map(function ($signature) {
            return self::makeWhitespaceSafe($signature);
        }, $signatures);
    }
}
