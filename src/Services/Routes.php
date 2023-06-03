<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request ;

class Routes
{

    private static function isInclude(Request $request, array $routes): bool
    {
        $onlyRoutes = $routes['only'] ?? false;

        // Include route if nothing specified
        if (empty($onlyRoutes)) {
            return true;
        }

        foreach ($onlyRoutes as $only) {
            static::checkValid($only);

            if ($request->is($only)) {
                return true;
            }
        }

        return false;
    }

    private static function isExcluded(Request $request, array $routes): bool
    {
        $exceptRoutes = $routes['except'];

        // Do not exclude if nothing specified
        if (empty($exceptRoutes)) {
            return false;
        }

        foreach ($exceptRoutes as $exclude) {
            static::checkValid($exclude);
            if ($request->is($exclude)) {
                return true;
            }
        }

        return false;
    }

    public static function skipRoute(Request $request, array $routesConfig): bool
    {
        if ( !$routesConfig) {
            return false;
        }

        $included = self::isInclude($request, $routesConfig);
        if (!$included) {
            return true;
        }

        $excluded = self::isExcluded($request, $routesConfig);
        if ($excluded) {
            return true;
        }

        return false;
    }

    private static function checkValid(string $route): void
    {
        if (str_starts_with($route, '/')) {
            throw new \Exception('routes cannot start with leading \\');
        }
    }
}
