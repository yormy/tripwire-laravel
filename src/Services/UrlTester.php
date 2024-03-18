<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;

class UrlTester
{
    public static function skipUrl(Request $request, ?array $urlsConfig): bool
    {
        if (! $urlsConfig) {
            return false;
        }

        $included = self::isInclude($request, $urlsConfig);
        if (! $included) {
            return true;
        }

        $excluded = self::isExcluded($request, $urlsConfig);
        if ($excluded) {
            return true;
        }

        return false;
    }

    /**
     * @param array<string> $urlsConfig
     */
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

    /**
     * @param array<string> $urlsConfig
     */
    private static function isExcluded(Request $request, array $urlsConfig): bool
    {
        $exceptUrls = $urlsConfig['except'];

        // Do not exclude if nothing specified
        if (empty($exceptUrls)) {
            return false;
        }

        foreach ($exceptUrls as $exclude) {
            static::checkValid($exclude);

            if ($request->is($exclude) || strcasecmp($exclude, $request->url()) === 0) {
                return true;
            }
        }

        return false;
    }

    protected static function checkValid(string $url): void
    {
        if (str_starts_with($url, '/')) {
            throw new \Exception('urls cannot start with leading \\');
        }
    }
}
