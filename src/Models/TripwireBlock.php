<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TripwireLaravel\Models\Traits\BlockScope;

class TripwireBlock extends BaseModel
{
    use BlockScope;
    use PackageFactoryTrait;

    protected $fillable = [
        'ignore',
        'blocked_ip',
        'blocked_user_id',
        'blocked_user_type',
        'blocked_browser_fingerprint',
        'blocked_until',
        'blocked_repeater',
        'manually_blocked',
        'persistent_block',
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('tripwire.database_tables.tripwire_blocks');

        parent::__construct($attributes);
    }

    public function getRouteKeyName()
    {
        return 'xid';
    }

    public function logs()
    {
        return $this->hasMany(TripwireLog::class)->withTrashed();
    }

    public function scopeByIp($query, string $ipAddress)
    {
        return $query->where('blocked_ip', $ipAddress);
    }

    public function user(): MorphTo
    {
        return $this->morphTo('user', 'blocked_user_type', 'blocked_user_id');
    }

    //    public function getRouteKeyName()
    //    {
    //        return 'xid';
    //    }
}
