<?php
namespace Yormy\TripwireLaravel\Models\Traits;

use Carbon\Carbon;

trait LogScope
{
    public function scopeWithin($query, int $minutes)
    {
        return $query->where('created_at', '>=', Carbon::now()->subMinutes($minutes));
    }

    public function scopeTypes($query, array $codes)
    {
        return $query->whereIn('event_code', $codes);
    }

    public function scopeByIp($query, string $ipAddress)
    {
        return $query->where('ip', $ipAddress);
    }

    public function scopeByUserId($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByUserType($query, string $userType)
    {
        return $query->where('user_type', $userType);
    }

    public function scopeByBrowser($query, string $browserFingerprint)
    {
        return $query->where('browser_fingerprint', $browserFingerprint);
    }
}
