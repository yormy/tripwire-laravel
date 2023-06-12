<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request ;

class UrlTester
{

    private static function isInclude(Request $request, array $urlsConfig): bool
    {
        $onlyUrls = $urlsConfig['only'] ?? false;

        // Include route if nothing specified
        if (empty($onlyUrls)) {
            return true;
        }

        foreach ($onlyUrls as $only) {
            static::checkValid($only);

            if ($request->is($only)) {
                return true;
            }
        }

        return false;
    }

    private static function isExcluded(Request $request, array $urlsConfig): bool
    {
        $exceptUrls = $urlsConfig['except'];

        // Do not exclude if nothing specified
        if (empty($exceptUrls)) {
            return false;
        }

        foreach ($exceptUrls as $exclude) {
            static::checkValid($exclude);

            if ($request->is($exclude) || 0 === strcasecmp($exclude, $request->url())) {
                return true;
            }
        }

        return false;
    }

    public static function skipUrl(Request $request, array $urlsConfig): bool
    {
        if ( !$urlsConfig) {
            return false;
        }

        $included = self::isInclude($request, $urlsConfig);
        if (!$included) {
            return true;
        }

        $excluded = self::isExcluded($request, $urlsConfig);
        if ($excluded) {
            return true;
        }

        return false;
    }

    private static function checkValid(string $url): void
    {
        if (str_starts_with($url, '/')) {
            throw new \Exception('urls cannot start with leading \\');
        }
    }
}
