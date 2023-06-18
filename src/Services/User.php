<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;

class User
{
    public static function getId(Request $request): ?string
    {
        return self::get($request)?->id;
    }

    public static function getType(Request $request): ?string
    {
        if (! self::get($request)) {
            return null;
        }

        return get_class(self::get($request));
    }

    private static function get(Request $request)
    {
        return $request->user();
    }
}
