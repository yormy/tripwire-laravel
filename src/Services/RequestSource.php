<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Jenssegers\Agent\Agent;

class RequestSource
{
    public static function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    public static function getPlatform(): string|bool
    {
        return (new Agent())->platform();
    }

    public static function getBrowser(): string|bool
    {
        return (new Agent())->browser();
    }

    public static function isTablet(): bool
    {
        return (new Agent())->isTablet();
    }

    public static function isMobile(): bool
    {
        return (new Agent())->isMobile();
    }

    public static function isPhone(): bool
    {
        return (new Agent())->isPhone();
    }

    public static function isDesktop(): bool
    {
        return (new Agent())->isDesktop();
    }

    public static function getReferer(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    public static function isRobot(): bool
    {
        return (new Agent())->isRobot();
    }

    public static function getRobot(): string|bool
    {
        $agent = new Agent();
        if ($agent->isRobot()) {
            return $agent->robot();
        }

        return '';
    }

    public static function getBrowserFingerprint(): string
    {
        /** @var string $browserFingerprintKey */
        $browserFingerprintKey = config('tripwire.cookie.browser_fingerprint');

        if (array_key_exists($browserFingerprintKey, $_COOKIE)) {
            return $_COOKIE[$browserFingerprintKey];
        }

        return '';
    }
}
