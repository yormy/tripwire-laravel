<?php

namespace Yormy\TripwireLaravel\Services;

use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request ;

class Routes
{
    public static function skipRoute(Request $request, array $routesConfig): bool
    {
        if ( !$routesConfig) {
            return false;
        }

        foreach ($routesConfig['except'] as $except) {
            if ( !static::isValid($except)) {
                throw new InvalidArgumentException('routes cannot start with leading \\');
            }

            if (! $request->is($except)) {
                continue;
            }

            return true;
        }

        foreach ($routesConfig['only'] as $only) {
            if ( !static::isValid($only)) {
                throw new InvalidArgumentException('routes cannot start with leading \\');
            }
            if ($request->is($only)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private static function isValid(string $route)
    {
        return !str_starts_with($route, '/');
    }
}
