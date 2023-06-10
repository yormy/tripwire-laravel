<?php

declare(strict_types=1);
namespace Yormy\TripwireLaravel\Models;

use Carbon\Carbon;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitType;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitTypeAuthFailed;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitTypeHackAttempt;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Yormy\TripwireLaravel\Models\Traits\BlockScope;
use Yormy\Xid\Models\Traits\Xid;

class TripwireBlock extends BaseModel
{
    //use DatabaseEncryption;
  //  use Xid;
    use BlockScope;
    use QueryCacheable;

    // Cachables
    public $cacheFor = 4 * (60 * 60); // cache time, in seconds
    protected static $flushCacheOnUpdate = true;

//    protected $encryptableSearch = [
//        'ip_address',
//        'user_agent'
//    ];

    protected $fillable = [
        'ignore',
        'blocked_ip',
        'blocked_user_id',
        'blocked_user_type',
        'blocked_browser_fingerprint',
        'blocked_until',
        'blocked_repeater',
        'manually_blocked',
        'persistent_block'
    ];

    protected $casts = [
        'blocked_until' => 'datetime'
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
