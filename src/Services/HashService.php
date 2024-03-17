<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class HashService
{
    public static function create(string $message): string
    {
        return self::sha512($message);
    }

    public static function sha256(string $message): string
    {
        return hash('sha256', $message);
    }

    public static function sha512(string $message): string
    {
        return hash('sha512', $message);
    }
}
