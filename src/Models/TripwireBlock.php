<?php

declare(strict_types=1);
namespace Yormy\TripwireLaravel\Models;

use Carbon\Carbon;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitType;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitTypeAuthFailed;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitTypeHackAttempt;
//use Mexion\BedrockCore\Services\RequestSource;
//use Mexion\BedrockCore\Traits\DatabaseEncryption;
//use Mexion\BedrockUsers\Interfaces\UserInterface;
//use Rennokki\QueryCache\Traits\QueryCacheable;
use Yormy\TripwireLaravel\Models\Traits\BlockScope;
use Yormy\Xid\Models\Traits\Xid;

class TripwireBlock extends BaseModel
{
    //use DatabaseEncryption;
    use Xid;
    use BlockScope;
//    use QueryCacheable;

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
//
//    public static function addTry(
//        string $reason = '',
//        TarpitType $tarpitType = null,
//        string $ip_address = '',
//        string $message = '',
//        string $details = null,
//        int $userId = null
//    ) {
//        $tarpit = self::getTarpitFromIp($ip_address);
//        if (!$tarpit) {
//            $tarpit = new Tarpit();
//            $tarpit->ip_address = $ip_address;
//        }
//
//        $tarpit->tries += 1;
//        $tarpit->block_until = self::getBlockedUntil($tarpit);
//
//        if ($reason) {
//            $reasons = [];
//
//            if ($tarpit->reason) {
//                $reasons = json_decode($tarpit->reason, true);
//            }
//            $reasons[] = $reason;
//            $tarpit->reasons = json_encode($reasons);
//        }
//
//        $requestSourceClass = config('tripwire.services.request_source');
//        $tarpit->user_agent = $requestSourceClass::getUserAgent();
//        $tarpit->browser_fingerprint = $requestSourceClass::getBrowserFingerprint();
//
//        $tarpit->message = $message;
//
//        if (!$tarpitType) {
//            $tarpitType = new TarpitTypeHackAttempt();
//        }
//        $tarpit->type = $tarpitType::getCode();
//
//        if ($details) {
//            $details = json_encode($details);
//        }
//        $tarpit->details = $details;
//        $tarpit->user_id = $userId;
//
//        $tarpit->save();
//    }
//
//    public static function isBlocked(string $ip_address): bool
//    {
//        $tarpit = self::getTarpitFromIp($ip_address);
//        if (!$tarpit) {
//            return false;
//        }
//
//        if (Carbon::parse($tarpit->block_until) < Carbon::now()) {
//            return false;
//        }
//        return true;
//    }
//
//    private static function blockedUntil(string $ip_address, TarpitType $tarpitType, UserInterface $user = null)
//    {
//        $tarpit = self::getTarpitFromIp($ip_address, $tarpitType);
//        if ($tarpit && Carbon::parse($tarpit->block_until) > Carbon::now()) {
//            return Carbon::parse($tarpit->block_until);
//        }
//
//        $tarpit = self::getTarpitFromUser($user, $tarpitType);
//        if ($tarpit && Carbon::parse($tarpit->block_until) > Carbon::now()) {
//            return Carbon::parse($tarpit->block_until);
//        }
//
//        return false;
//    }
//
//    public static function blockedAuthFailedUntil(string $ip_address, UserInterface $user = null)
//    {
//        return self::blockedUntil($ip_address, new TarpitTypeAuthFailed(), $user);
//    }
//
//    public static function blockedHackAttemptUntil(string $ip_address, UserInterface $user = null)
//    {
//        return self::blockedUntil($ip_address, new TarpitTypeHackAttempt(), $user);
//    }
//
//    public static function remove(string $ip_address)
//    {
//        $tarpit = self::getTarpitFromIp($ip_address);
//        if ($tarpit) {
//            $tarpit->delete();
//        }
//    }
//
//    private static function getTarpitFromUser(?UserInterface $user, TarpitType $type = null): ?Tarpit
//    {
//        if (!$user) {
//            return null;
//        }
//
//        $query = Tarpit::where('user_id', $user->id);
//        if ($type) {
//            $query->where('type', $type::getCode());
//        }
//
//        return $query->first();
//    }
//
//    private static function getTarpitFromIp(string $ip_address, TarpitType $type = null): ?Tarpit
//    {
//        $query = Tarpit::whereEncrypted('ip_address', $ip_address);
//
//        if ($type) {
//            $query->where('type', $type::getCode());
//        }
//
//        return $query->first();
//    }
//
//    private static function getBlockedUntil(Tarpit $tarpit)
//    {
//        $graceTries = config('bedrock-core.security.tarpit.grace_tries');
//        $penaltySeconds = config('bedrock-core.security.tarpit.penalty_seconds');
//
//        $penaltyTries = max($tarpit->tries - $graceTries, 0);
//        $penaltySquared = $penaltyTries * $penaltyTries;
//
//        $penaltyInSeconds = $penaltySquared * $penaltySeconds;
//
//        return Carbon::now()->addSeconds($penaltyInSeconds);
//    }

//    public function getRouteKeyName()
//    {
//        return 'xid';
//    }
}
