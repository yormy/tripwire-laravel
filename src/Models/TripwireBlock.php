<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Yormy\TripwireLaravel\Models\Traits\BlockScope;

class TripwireBlock extends BaseModel
{
    use BlockScope;

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

    //    public function getRouteKeyName()
    //    {
    //        return 'xid';
    //    }
}
