<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TripwireLaravel\Models\Traits\BlockScope;

/**
 * @property string $xid
 * @property bool $ignore
 * @property string $type
 * @property array<string> $reasons
 * @property string $blocked_ip
 * @property bool $persistent_block
 * @property string $blocked_browser_fingerprint
 * @property int $blocked_repeater
 * @property string $internal_comments
 * @property bool $manually_blocked
 * @property string $created_at
 * @property string $deleted_at
 * @property string $blocked_until
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Yormy\TripwireLaravel\Models\TripwireLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byBrowser(string $browserFingerprint)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byIp(string $ipAddress)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byUserId(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock byUserType(string $userType)
 * @method static \Yormy\TripwireLaravel\Database\Factories\TripwireBlockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock notIgnore()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock withinDays(int $days)
 * @method static \Illuminate\Database\Eloquent\Builder|TripwireBlock withoutTrashed()
 * @mixin \Eloquent
 */
class TripwireBlock extends BaseModel
{
    use BlockScope;
    use PackageFactoryTrait;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var array<string> $fillable
     */
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
        'internal_comments',
    ];

    /**
     * @var array<string>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $casts = [
        'blocked_until' => 'datetime',
    ];

    /**
     * @param array<string> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('tripwire.database_tables.tripwire_blocks');

        parent::__construct($attributes);
    }

    public function getRouteKeyName(): string
    {
        return 'xid';
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TripwireLog::class)->withTrashed();
    }

    public function scopeByIp(Builder $query, string $ipAddress): Builder
    {
        return $query->where('blocked_ip', $ipAddress);
    }

    public function user(): MorphTo
    {
        return $this->morphTo('user', 'blocked_user_type', 'blocked_user_id');
    }
}
