<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Yormy\TripwireLaravel\DataObjects\Block\BlockDataRequest;
use Yormy\TripwireLaravel\Models\TripwireBlock;

class BlockRepository
{
    private Model $model;

    private int $repeatOffenderTimeframeDays;

    public function __construct()
    {
        $class = config('tripwire.models.block');
        $this->model = new $class(); // @phpstan-ignore-line

        $this->repeatOffenderTimeframeDays = 10; // how long to look back for repeating violations
    }

    public function getAll(): Collection
    {
        return $this->model::with('user')->latest()->get();
    }

    public function getAllForUser(Authenticatable $user): Collection
    {
        $foreignUserId = config('tripwire.user_fields.foreign_key');

        return $this->model::latest()
            ->byUserId($user->$foreignUserId)
            ->byUserType($user::class)
            ->withTrashed()
            ->get();
    }

    public function findByXid(string $xid): ?Model
    {
        return $this->model::where('xid', $xid)
            ->withTrashed()
            ->get()
            ->first();
    }

    public function resetIp(string $ip, bool $softDelete = true): void
    {
        $query = $this->model::byIp($ip)
            ->where('persistent_block', false);

        $this->delete($query, $softDelete);
    }

    public function resetBrowser(?string $browserFingerprint, bool $softDelete = true): void
    {
        if (! $browserFingerprint) {
            return;
        }

        $query = $this->model::where('blocked_browser_fingerprint', $browserFingerprint)
            ->where('persistent_block', false);

        $this->delete($query, $softDelete);
    }

    public function resetUser(?int $userId, ?string $userType, bool $softDelete = true): void
    {
        if (! $userId) {
            return;
        }
        $query = $this->model::where('blocked_user_id', $userId)
            ->where('blocked_user_type', $userType)
            ->where('persistent_block', false);
        $this->delete($query, $softDelete);
    }

    public function addManualBlock(BlockDataRequest $data): Model
    {
        $data = $data->toArray();
        $data['manually_blocked'] = true;
        $data['persistent_block'] = true;
        $data['blocked_until'] = Carbon::now()->addYears(100);

        return $this->model->create($data);
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
        $data = [];
        $data['ignore'] = $ignore;
        $data['blocked_ip'] = $ipAddress;
        $data['blocked_user_id'] = $userId;
        $data['blocked_user_type'] = $userType;
        $data['blocked_browser_fingerprint'] = $browserFingerprint;

        $repeaterCount = $this->getRepeaterCount($ipAddress, $userId, $userType, $browserFingerprint);
        $blockedUntil = $this->getBlockedUntil($penaltySeconds, $repeaterCount);
        $data['blocked_until'] = $blockedUntil;
        $data['blocked_repeater'] = $repeaterCount;

        $data['response_json'] = $responseJson;
        $data['response_html'] = $responseHtml;

        return $this->model::create($data);
    }

    public function isIpBlockedUntil(string $ipAddress): ?CarbonImmutable
    {
        $builder = $this->model
            ->byIp($ipAddress)
            ->where('blocked_until', '>', Carbon::now());

        $blocked = $this->getLatest($builder);

        return $blocked?->blocked_until;
    }

    public function isBrowserBlockedUntil(string $browserFingerprint): ?CarbonImmutable
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

    public function isUserBlockedUntil(int $userId, string $userType): ?CarbonImmutable
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

        $builder->where(function ($query) use ($ipAddress, $browserFingerprint, $userId, $userType): void {
            $query = $query->byIp($ipAddress);

            if ($browserFingerprint) {
                $query->orWhere('blocked_browser_fingerprint', $browserFingerprint);
            }

            if ($userId) {
                $query->orWhere(function ($queryUser) use ($userId, $userType): void {
                    $queryUser
                        ->where('blocked_user_type', $userType)
                        ->where('blocked_user_id', $userId);
                });
            }
        });

        $blocked = $this->getLatest($builder);

        return $blocked?->blocked_until;
    }

    public function getRepeaterPunishment(int $penaltySeconds, int $repeaterCount): int
    {
        if (! $repeaterCount) {
            return 0;
        }

        // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
        return (int) pow($penaltySeconds, $repeaterCount);
    }

    private function delete(Builder $query, bool $softDelete = true): void
    {
        if (! $softDelete) {
            $query->forceDelete();

            return;
        }

        $query->delete();
    }

    private function getLatest(Builder $builder): ?TripwireBlock
    {
        return $builder->notIgnore()
            ->latest()
            ->first();
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

        $repeatOffenderBrowser = 0;
        if ($browserFingerprint) {
            $repeatOffenderBrowser = $this->repeatOffenderBrowser($browserFingerprint);
        }

        return max($repeatOffenderIp, $repeatOffenderUser, $repeatOffenderBrowser);
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

    private function queryRepeatOffender(): Builder
    {
        return $this->model
            ->withinDays($this->repeatOffenderTimeframeDays);
    }
}
