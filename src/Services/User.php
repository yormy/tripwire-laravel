<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;

class User
{
    public static function getId(Request $request): ?string
    {
        return self::get($request)?->id;
    }

    public static function getType(Request $request): ?string
    {
        if (!self::get($request)) {
            return null;
        }

        return get_class(self::get($request));
    }


    private static function get(Request $request)
    {
        return $request->user();
    }
}
