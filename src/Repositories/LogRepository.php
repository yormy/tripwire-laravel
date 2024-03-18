<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;

class LogRepository
{
    private Model $model;

    public function __construct()
    {
        $class = config('tripwire.models.log');
        $this->model = new $class(); // @phpstan-ignore-line
    }

    public function getAll(): Collection
    {
        return $this->model::with(['user', 'block'])->latest()->get();
    }

    public function getAllForUser(Authenticatable $user): Collection
    {
        $foreignUserId = config('tripwire.user_fields.foreign_key');

        return $this->model::with(['block'])
            ->latest()
            ->byUserId($user->$foreignUserId)
            ->byUserType($user::class)
            ->withTrashed()
            ->get();
    }

    public function getByBlockId(int $blockId): Collection
    {
        return $this->model::where('tripwire_block_id', $blockId)->latest()->get();
    }

    /**
     * @param array<string> $meta
     */
    public function add(LoggableEventInterface $event, array $meta): Model
    {
        $data = $meta;
        $data['event_code'] = $event::CODE;
        $data['event_score'] = $event->getScore();
        $data['event_violation'] = substr($event->getViolationText(), 0, 1000);
        $data['event_comment'] = $event->getComment();
        $data['ignore'] = $event->getTrainingMode();

        if ($event->getDebugMode()) {
            $data['trigger_data'] = $event->getTriggerData();
            $data['trigger_rule'] = substr(implode(';', $event->getTriggerRules()), 0, 1000);
        }

        return $this->model::create($data);
    }

    public function resetIp(string $ip, bool $softDelete = true): void
    {
        $query = $this->model::byIp($ip);
        $this->delete($query, $softDelete);
    }

    public function resetBrowser(?string $browserFingerprint, bool $softDelete = true): void
    {
        if (! $browserFingerprint) {
            return;
        }

        $query = $this->model::where('browser_fingerprint', $browserFingerprint);
        $this->delete($query, $softDelete);
    }

    public function resetUser(?int $userId, ?string $userType, bool $softDelete = true): void
    {
        if (! $userId) {
            return;
        }
        $query = $this->model::where('user_id', $userId)
            ->where('user_type', $userType);
        $this->delete($query, $softDelete);
    }

    /**
     * @param array<string> $violations
     */
    public function queryViolationsByIp(int $withinMinutes, string $ipAddress, array $violations = []): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byIp($ipAddress);
    }

    /**
     * @param array<string> $violations
     */
    public function queryViolationsByUser(int $withinMinutes, int $userId, ?string $userType, array $violations = []): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byUserType($userType)
            ->byUserId($userId);
    }

    /**
     * @param array<string> $violations
     */
    public function queryViolationsByBrowser(
        int $withinMinutes,
        string $browserFingerprint,
        array $violations = []
    ): Builder {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byBrowser($browserFingerprint);
    }

    private function delete(Builder $query, bool $softDelete = true): void
    {
        if (! $softDelete) {
            $query->forceDelete();

            return;
        }

        $query->delete();
    }

    /**
     * @param array<string> $violations
     */
    private function queryScoreViolations(int $withinMinutes, array $violations = []): Builder
    {
        $builder = $this->model->within($withinMinutes);

        if (! empty($violations)) {
            $builder->types($violations);
        }

        return $builder;
    }
}
