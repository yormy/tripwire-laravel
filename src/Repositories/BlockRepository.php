<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Yormy\TripwireLaravel\Models\TripwireBlock;

class BlockRepository
{
    private Model $model;

    private int $repeatOffenderTimeframeDays;

    public function __construct()
    {
        $class = config('tripwire.models.block');
        $this->model = new $class;

        $this->repeatOffenderTimeframeDays = 10; // how long to look back for repeating violations
    }

    public function getAll(): Collection
    {
        return $this->model::with('user')->latest()->get();
    }

    public function getAllForUser($user): Collection
    {
        return $this->model::latest()
            ->byUserId($user->id)
            ->byUserType(get_class($user))
            ->withTrashed()
            ->get();
    }

    public function findByXid(string $xid): ?TripwireBlock
    {
        return $this->model::where('xid', $xid)
            ->withTrashed()
            ->get()
            ->first();
    }

    /**
     * @return void
     */
    private function delete(Builder $query, bool $softDelete = true)
    {
        if (! $softDelete) {
            $query->forceDelete();

            return;
        }

        $query->delete();
    }

    public function resetIp(string $ip, bool $softDelete = true): void
    {
        $query = $this->model::byIp($ip)
            ->where('persistent_block', false);

        $this->delete($query, $softDelete);
    }

    /**
     * @return void
     */
    public function resetBrowser(?string $browserFingerprint, bool $softDelete = true)
    {
        if (! $browserFingerprint) {
            return;
        }

        $query = $this->model::where('blocked_browser_fingerprint', $browserFingerprint)
            ->where('persistent_block', false);

        $this->delete($query, $softDelete);
    }

    /**
     * @return void
     */
    public function resetUser(?int $userId, ?string $userType, bool $softDelete = true)
    {
        if (! $userId) {
            return;
        }
        $query = $this->model::where('blocked_user_id', $userId)
            ->where('blocked_user_type', $userType)
            ->where('persistent_block', false);
        $this->delete($query, $softDelete);
    }

    public function add(
        int $penaltySeconds,
        string $ipAddress,
        ?int $userId,
        ?string $userType,
        ?string $browserFingerprint,
        ?string $responseJson,
        ?string $responseHtml,
        ?bool $ignore = false
    ): Model {
        $data['ignore'] = $ignore;
        $data['blocked_ip'] = $ipAddress;
        $data['blocked_user_id'] = $userId;
        $data['blocked_user_type'] = $userType;
        $data['blocked_browser_fingerprint'] = $browserFingerprint;

        $repeaterCount = $this->getRepeaterCount($ipAddress, $userId, $userType, $browserFingerprint);
        $blockedUntil = $this->getBlockedUntil($penaltySeconds, $repeaterCount);
        $data['blocked_until'] = $blockedUntil;
        $data['blocked_repeater'] = $repeaterCount;

        //        $data['response_json'] = $responseJson;
        //        $data['response_html'] = $responseHtml;

        return $this->model::create($data);
    }

    private function getLatest(Builder $builder)
    {
        return $builder->notIgnore()
            ->latest()
            ->first();
    }

    public function isIpBlockedUntil(string $ipAddress): ?Carbon
    {
        $builder = $this->model
            ->byIp($ipAddress)
            ->where('blocked_until', '>', Carbon::now());

        $blocked = $this->getLatest($builder);

        return $blocked?->blocked_until;
    }

    public function isBrowserBlockedUntil(string $browserFingerprint): ?Carbon
    {
        if (! $browserFingerprint) {
            return null;
        }

        $builder = $this->model
            ->where('blocked_browser_fingerprint', $browserFingerprint)
            ->where('blocked_until', '>', Carbon::now());

        $blocked = $this->getLatest($builder);

        return $blocked?->blocked_until;
    }

    public function isUserBlockedUntil(int $userId, string $userType): ?Carbon
    {
        if (! $userId) {
            return null;
        }

        $builder = $this->model
            ->where('blocked_user_type', $userType)
            ->where('blocked_user_id', $userId)
            ->where('blocked_until', '>', Carbon::now());

        $blocked = $this->getLatest($builder);

        return $blocked?->blocked_until;
    }

    public function isAnyBlockedUntil(
        string $ipAddress,
        ?string $browserFingerprint,
        ?int $userId,
        ?string $userType,
    ): ?Carbon {
        $builder = $this->model
            ->where('blocked_until', '>', Carbon::now());

        $builder->where(function ($query) use ($ipAddress, $browserFingerprint, $userId, $userType) {
            $query = $query->byIp($ipAddress);

            if ($browserFingerprint) {
                $query->orWhere('blocked_browser_fingerprint', $browserFingerprint);
            }

            if ($userId) {
                $query->orWhere(function ($queryUser) use ($userId, $userType) {
                    $queryUser
                        ->where('blocked_user_type', $userType)
                        ->where('blocked_user_id', $userId);
                });
            }
        });

        $blocked = $this->getLatest($builder);

        return $blocked?->blocked_until;
    }

    private function getBlockedUntil(
        int $penaltySeconds,
        int $repeaterCount
    ): Carbon {
        $repeaterPunishment = $this->getRepeaterPunishment($penaltySeconds, $repeaterCount);
        $blockedUntil = Carbon::now()->addSeconds($penaltySeconds + $repeaterPunishment);

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
        $repeatOffenderUser = $this->repeatOffenderUser($userId, $userType);
        $repeatOffenderBrowser = $this->repeatOffenderBrowser($browserFingerprint);

        return max($repeatOffenderIp, $repeatOffenderUser, $repeatOffenderBrowser);
    }

    public function getRepeaterPunishment(int $penaltySeconds, int $repeaterCount): int
    {
        if (! $repeaterCount) {
            return 0;
        }

        // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
        return (int) pow($penaltySeconds, $repeaterCount);
    }

    private function repeatOffenderIp(string $ipAddress): int
    {
        return $this->queryRepeatOffender()
            ->byIp($ipAddress)
            ->count();
    }

    private function repeatOffenderUser(?int $userId, ?string $userType): int
    {
        if (! $userId) {
            return 0;
        }

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
