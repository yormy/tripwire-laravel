<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yormy\TripwireLaravel\Models\TripwireBlock;

class BlockRepository
{
    private Model $model;

    private int $repeatOffenderTimeframeDays;

    public function __construct()
    {
        $this->model = new TripwireBlock();
        $this->repeatOffenderTimeframeDays = 10; // how long to look back for repeating violations
    }

    public function add(
        int $penaltySeconds,
        string $ipAddress,
        ?int $userId,
        ?string $userType,
        ?string $browserFingerprint,
    )
    {
        $data['blocked_ip'] = $ipAddress;
        $data['blocked_user_id'] = $userId;
        $data['blocked_user_type'] = $userType;
        $data['blocked_browser_fingerprint'] = $browserFingerprint;

        $repeaterCount = $this->getRepeaterCount($ipAddress, $userId, $userType, $browserFingerprint);
        $blockedUntil = $this->getBlockedUntil($penaltySeconds, $repeaterCount);
        $data['blocked_until'] = $blockedUntil;
        $data['blocked_repeater'] = $repeaterCount;

        return $this->model::create($data);
    }

    private function getBlockedUntil(
        int $penaltySeconds,
        int $repeaterCount
    ): Carbon {
        $repeaterPunishment = $this->getRepeaterPunishment($penaltySeconds, $repeaterCount);
        $blockedUntil =  Carbon::now()->addMinutes($penaltySeconds + $repeaterPunishment);

        $maxDate = Carbon::now()->addYears(1000);    // this prevents a buffer overflow of carbon
        if ($blockedUntil > $maxDate) {
            return $maxDate;
        }

        return $blockedUntil;
    }

    private function getRepeaterCount(
        string $ipAddress,
        ?int $userId,
        ?string $userType,
        ?string $browserFingerprint,
    ): int {
        $repeatOffenderIp = $this->repeatOffenderIp($ipAddress);
        $repeatOffenderUser = $this->repeatOffenderUser($userId, $userType );
        $repeatOffenderBrowser = $this->repeatOffenderBrowser($browserFingerprint );

        return max($repeatOffenderIp, $repeatOffenderUser, $repeatOffenderBrowser);
    }

    public function getRepeaterPunishment(int $penaltySeconds, int $repeaterCount): int
    {
        if (!$repeaterCount) {
            return 0;
        }

        // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
        return pow($penaltySeconds, $repeaterCount);
    }

    private function repeatOffenderIp(string $ipAddress): int
    {
        return $this->queryRepeatOffender()
            ->byIp($ipAddress)
            ->count();
    }

    private function repeatOffenderUser(int $userId, string $userType): int
    {
        return $this->queryRepeatOffender()
            ->byUserId($userId)
            ->byUserType($userType)
            ->count();
    }

    private function repeatOffenderBrowser(string $browserFingerprint): int
    {
        return $this->queryRepeatOffender()
            ->byBrowser($browserFingerprint)
            ->count();
    }

    private function queryRepeatOffender()
    {
        return $this->model
            ->withinDays($this->repeatOffenderTimeframeDays);
    }
}

