<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Regex
{
    public static function forbidden(array $signatures): string
    {
        return '#('. implode('|', $signatures) .')#iUu';
    }
}
