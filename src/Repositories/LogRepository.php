<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;

class LogRepository
{
    private Model $model;

    public function __construct()
    {
        $class= config('tripwire.models.log');
        $this->model = new $class;
    }

    public function getAll(): Collection
    {
        return $this->model::latest()->get();
    }

    public function getByBlockId(int $blockId): Collection
    {
        return $this->model::where('tripwire_block_id', $blockId)->latest()->get();
    }


    public function add(LoggableEventInterface $event, array $meta)
    {
        $data = $meta;
        $data['event_code'] = $event::CODE;
        $data['event_score'] = $event->getScore();
        $data['event_violation'] = $event->getViolationText();
        $data['event_comment'] = $event->getComment();

        return $this->model::create($data);
    }

    private function delete(Builder $query, bool $softDelete = true)
    {
        if (!$softDelete) {
            $query->forceDelete();
            return;
        }

        $query->delete();
    }

    public function resetIp(string $ip, bool $softDelete = true)
    {
        $query = $this->model::where('ip', $ip);
        $this->delete($query, $softDelete);
    }


    public function resetBrowser(?string $browserFingerprint, bool $softDelete = true)
    {
        if (!$browserFingerprint) {
            return;
        }

        $query = $this->model::where('browser_fingerprint', $browserFingerprint);
        $this->delete($query, $softDelete);
    }

    public function resetUser(?int $userId, ?string $userType, bool $softDelete = true)
    {
        if (!$userId) {
            return;
        }
        $query = $this->model::where('user_id', $userId)
            ->where('user_type', $userType);
        $this->delete($query, $softDelete);
    }

    public function queryViolationsByIp(int $withinMinutes, string $ipAddress, array $violations = []): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byIp($ipAddress);

    }

    public function queryViolationsByUser(int $withinMinutes, $userId, $userType, array $violations = []): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byUserId($userId)
            ->byUserType($userType);
    }

    public function queryViolationsByBrowser(int $withinMinutes, string $browserFingerprint, array $violations = []): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byBrowser($browserFingerprint);
    }

    private function queryScoreViolations(int $withinMinutes, array $violations = []): Builder
    {
       $builder = $this->model->within($withinMinutes);

       if (!empty($violations)) {
           $builder->types($violations);
       }

       return $builder;
    }
}
