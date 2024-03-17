<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;

trait LogScope
{
    public function scopeWithin(Builder $query, int $minutes): Builder
    {
        return $query
            ->where('created_at', '>=', Carbon::now()->subMinutes($minutes))
            ->whereNull('tripwire_block_id');
    }

    /**
     * @param array<string> $codes
     */
    public function scopeTypes(Builder $query, array $codes): Builder
    {
        return $query->whereIn('event_code', $codes);
    }

    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByUserType(Builder $query, string $userType): Builder
    {
        return $query->where('user_type', $userType);
    }

    public function scopeByBrowser(Builder $query, string $browserFingerprint): Builder
    {
        return $query->where('browser_fingerprint', $browserFingerprint);
    }
}
