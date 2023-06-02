<?php

namespace Yormy\TripwireLaravel\Services;

use Jenssegers\Agent\Agent;

class RequestSource
{
    public static function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function isRobot(): bool
    {
        $agent = new Agent();
        return $agent->isRobot();
    }

    public static function getRobot(): string
    {
        $agent = new Agent();
        if ($agent->isRobot()) {
            return $agent->robot();
        }
        return '';
    }

    public static function getBrowserFingerprint(): string
    {
        $browserFingerprintKey = config('tripwire.cookie.browser_fingerprint');
        if (array_key_exists($browserFingerprintKey, $_COOKIE)) {
            return $_COOKIE[$browserFingerprintKey];
        }
        return '';
    }
}
