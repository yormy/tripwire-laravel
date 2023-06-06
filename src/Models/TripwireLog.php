<?php
namespace Yormy\TripwireLaravel\Models;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockCore\Http\Traits\DatatableSearchable;
use Mexion\BedrockCore\Services\RequestSource;
use Mexion\BedrockCore\Traits\DatabaseEncryption;
use Mexion\BedrockUsers\Models\Member;
use Yormy\Dateformatter\Models\Traits\DateFormatter;
use Yormy\Xid\Models\Traits\Xid;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Yormy\TripwireLaravel\Models\Traits\LogScope;

class TripwireLog extends BaseModel
{
    use Xid;
    use QueryCacheable;
    use LogScope;
//    use DatatableSearchable;
//    use DateFormatter;
//    use DatabaseEncryption;

    protected $encryptableSearch = [
        //'ip' // do not encrypt IP, this is used for determining if we need to block the ip or not
    ];

    protected $fillable = [
        'event_code',
        'event_score',
        'event_violation',
        'event_comment',
        'ip',
        'ips',
        'xid',
        'middleware',
        'level',
        'user_id',
        'user_type',
        'url',
        'method',
        'referer',
        'header',
        'request',
        'user_agent',
        'robot_crawler',
        'browser_fingerprint',
        'request_fingerprint',
        'tripwire_block_id'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'id', 'pivot'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('tripwire.database_tables.tripwire_log');

        parent::__construct($attributes);
    }

//    public function user()
//    {
//        return $this->belongsTo(Member::class, 'user_id');
//    }

}
