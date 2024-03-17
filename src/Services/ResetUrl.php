<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class ResetUrl
{
    public static function get(?int $expirationMinutes = null): ?string
    {
        if (! config('tripwire.reset.enabled')) {
            return null;
        }

        if (! $expirationMinutes) {
            $expirationMinutes = config('tripwire.reset.link_expiry_minutes', 60);
        }
        $expiresAt = Carbon::now()->addMinutes($expirationMinutes);

        return URL::temporarySignedRoute('tripwire.guest.logs.reset', $expiresAt);
    }
}
