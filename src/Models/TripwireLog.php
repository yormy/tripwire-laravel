<?php
declare(strict_types=1);

namespace Yormy\TripwireLaravel\Models;

use Yormy\TripwireLaravel\Models\Traits\LogScope;

class TripwireLog extends BaseModel
{
    use LogScope;

    protected $fillable = [
        'event_code',
        'event_score',
        'event_violation',
        'event_comment',
        'ip',
        'ips',
        'xid',
        'level',
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
        'tripwire_block_id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('tripwire.database_tables.tripwire_log');

        parent::__construct($attributes);
    }
}
