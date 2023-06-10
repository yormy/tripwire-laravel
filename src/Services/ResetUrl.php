<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ResetUrl
{
    public static function get(int $expirationMinutes = null)
    {
        if (!$expirationMinutes) {
            $expirationMinutes = config('tripwire.reset.link_expiry_minutes', 60);
        }
        $expiresAt = Carbon::now()->addMinutes($expirationMinutes);

        return URL::temporarySignedRoute('tripwire.guest.logs.reset', $expiresAt);
    }
}