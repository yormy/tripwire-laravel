<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TripwireLaravel\Models\Traits\LogScope;

class TripwireLog extends BaseModel
{
    use LogScope;
    use PackageFactoryTrait;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected $fillable = [
        'ignore',
        'event_code',
        'event_score',
        'event_violation',
        'event_comment',
        'ip',
        'ips',
        'xid',
        'user_id',
        'user_type',
        'url',
        'method',
        'referer',
        'header',
        'request',
        'user_agent',
        'trigger_data',
        'trigger_rule',
        'robot_crawler',
        'browser_fingerprint',
        'request_fingerprint',
        'tripwire_block_id',
    ];

    /**
     * @param array<string> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('tripwire.database_tables.tripwire_logs');

        parent::__construct($attributes);
    }

    public function scopeByIp(Builder $query, string $ipAddress)
    {
        return $query->where('ip', $ipAddress);
    }

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function block()
    {
        return $this->belongsTo(TripwireBlock::class, 'tripwire_block_id', 'id')->withTrashed();
    }
}
