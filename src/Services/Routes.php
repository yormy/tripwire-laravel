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
            static::checkValid($except);

            if (! $request->is($except)) {
                continue;
            }

            return true;
        }

        foreach ($routesConfig['only'] as $only) {
            static::checkValid($except);

            if ($request->is($only)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private static function checkValid(string $route): void
    {
        if (!str_starts_with($route, '/')) {
            throw new InvalidArgumentException('routes cannot start with leading \\');
        }
    }
}
