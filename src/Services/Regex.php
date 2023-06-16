<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Regex
{
    public static function forbidden(array $signatures, string $delim = '#'): string
    {
        return $delim .'('. self::or($signatures) .')'. $delim. 'iUu';
    }

    public static function or(array $signatures): string
    {
        return implode('|', $signatures);
    }
}
