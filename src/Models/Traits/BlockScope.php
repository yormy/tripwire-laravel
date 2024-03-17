<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;

trait BlockScope
{
    public function scopeNotIgnore(Builder $query)
    {
        return $query->where('ignore', false);
    }

    public function scopeWithinDays(Builder $query, int $days)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeByUserId(Builder $query, int $userId)
    {
        return $query->where('blocked_user_id', $userId);
    }

    public function scopeByUserType(Builder $query, string $userType)
    {
        return $query->where('blocked_user_type', $userType);
    }

    public function scopeByBrowser(Builder $query, string $browserFingerprint)
    {
        return $query->where('blocked_browser_fingerprint', $browserFingerprint);
    }
}
