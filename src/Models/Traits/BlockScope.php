<?php
namespace Yormy\TripwireLaravel\Models\Traits;

use Carbon\Carbon;

trait BlockScope
{
    public function scopeWithinDays($query, int $days)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeByIp($query, string $ipAddress)
    {
        return $query->where('blocked_ip', $ipAddress);
    }

    public function scopeByUserId($query, int $userId)
    {
        return $query->where('blocked_user_id', $userId);
    }

    public function scopeByUserType($query, string $userType)
    {
        return $query->where('blocked_user_type', $userType);
    }

    public function scopeByBrowser($query, string $browserFingerprint)
    {
        return $query->where('blocked_browser_fingerprint', $browserFingerprint);
    }
}
