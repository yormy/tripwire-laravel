<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;

trait BlockScope
{
    public function scopeNotIgnore(Builder $query): Builder
    {
        return $query->where('ignore', false);
    }

    public function scopeWithinDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('blocked_user_id', $userId);
    }

    public function scopeByUserType(Builder $query, string $userType): Builder
    {
        return $query->where('blocked_user_type', $userType);
    }

    public function scopeByBrowser(Builder $query, string $browserFingerprint): Builder
    {
        return $query->where('blocked_browser_fingerprint', $browserFingerprint);
    }
}
