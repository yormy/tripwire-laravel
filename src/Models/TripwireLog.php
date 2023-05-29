<?php
namespace Yormy\TripwireLaravel\Models;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockCore\Http\Traits\DatatableSearchable;
use Mexion\BedrockCore\Services\RequestSource;
use Mexion\BedrockCore\Traits\DatabaseEncryption;
use Mexion\BedrockUsers\Models\Member;
use Yormy\Dateformatter\Models\Traits\DateFormatter;
use Yormy\Xid\Models\Traits\Xid;
use Illuminate\Database\Eloquent\Model;

class TripwireLog extends Model
{
//    use Xid;
//    use DatatableSearchable;
//    use DateFormatter;
//    use DatabaseEncryption;

    protected $table = 'tripwire_logs';

    protected $encryptableSearch = [
        //'ip' // do not encrypt IP, this is used for determining if we need to block the ip or not
    ];

    protected $fillable = [
        'ip',
        'xid',
        'middleware',
        'level',
        'user_id',
        'user_type',
        'url',
        'referrer',
        'request',
        'user_agent',
        'robot_crawler',
        'browser_fingerprint'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'id', 'pivot'
    ];

//    public function user()
//    {
//        return $this->belongsTo(Member::class, 'user_id');
//    }

}
