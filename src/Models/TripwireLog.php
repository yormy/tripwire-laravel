<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TripwireLaravel\Models\Traits\LogScope;

/**
 * @property string $url
 * @property string $xid
 * @property string $event_code
 * @property int $event_score
 * @property string $event_comment
 * @property string $event_violation
 * @property string $ip
 * @property string $referer
 * @property string $request
 * @property string $user_agent
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $deleted_at
 * @property string $method
 * @property int $tripwire_block_id
 * @property string $header
 * @property string $robot_crawler
 * @property string $trigger_data
 * @property string $trigger_rule
 * @property string $browser_fingerprint
 * @property bool $ignore
 * @property-read \Yormy\TripwireLaravel\Models\TripwireBlock|null $block
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byBrowser(string $browserFingerprint)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byIp(string $ipAddress)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byUserId(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog byUserType(string $userType)
 * @method static \Yormy\TripwireLaravel\Database\Factories\TripwireLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog types(array $codes)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog within(int $minutes)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireLog withoutTrashed()
 *
 * @mixin \Eloquent
 */
class TripwireLog extends BaseModel
{
    use LogScope;
    use PackageFactoryTrait;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var array<int, string>
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
     * @param  array<string>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('tripwire.database_tables.tripwire_logs'); // @phpstan-ignore-line

        parent::__construct($attributes);
    }

    public function scopeByIp(Builder $query, string $ipAddress): Builder
    {
        return $query->where('ip', $ipAddress);
    }

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(TripwireBlock::class, 'tripwire_block_id', 'id')->withTrashed();
    }
}
