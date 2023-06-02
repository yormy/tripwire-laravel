<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;
use Yormy\TripwireLaravel\Services\HashService;
use Yormy\TripwireLaravel\Services\RequestSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Yormy\TripwireLaravel\Services\User;

class LogRepository
{
    private Model $model;

    public function __construct()
    {
        $class= config('tripwire.models.log');
        $this->model = new $class;
    }

    public function add(Request $request, LoggableEventInterface $event)
    {
        $data['event_code'] = $event::CODE;
        $data['event_score'] = $event->getScore();
        $data['event_violation'] = $event->getViolationText();
        $data['event_comment'] = $event->getComment();
        $data = $this->addMeta($request, $data);

        return $this->model::create($data);
    }

    public function queryViolationsByIp(int $withinMinutes, array $violations, string $ipAddress): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byIp($ipAddress);

    }

    public function queryViolationsByUser(int $withinMinutes, array $violations, $userId, $userType): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byUserId($userId)
            ->byUserType($userType);
    }

    public function queryViolationsByBrowser(int $withinMinutes, array $violations, string $browserFingerprint): Builder
    {
        return $this->queryScoreViolations($withinMinutes, $violations)
            ->byBrowser($browserFingerprint);
    }

    private function queryScoreViolations(int $withinMinutes, array $violations): Builder
    {
       return $this->model
           ->within($withinMinutes)
           ->types($violations);
    }


    private function addMeta(Request $request, array $data): array
    {
        $data['ip'] = $request->ip();
        $data['ips'] = json_encode($request->ips());
        $data = $this->addRequest($request, $data);
        $data = $this->addUser($request, $data);
        $data = $this->addUserAgent($data);

        return $data;
    }

    private function addRequest(Request $request, array $data): array
    {
        $data['url'] = $request->fullUrl();
        $data['method'] = $request->method();
        $data['referer'] = $request->headers->get('referer');
        $data['header'] = json_encode($request->header());
        $data['request'] = json_encode($request->all());
        $data['request_fingerprint'] = $this->fingerprint($request);

        return $data;
    }


    private function addUser(Request $request, array $data): array
    {
        $userId = User::getId($request);
        $userType = User::getType($request);

        $data['user_id'] = $userId ?? null;
        $data['user_type'] = $userType  ?? null;

        return $data;
    }

    private function addUserAgent(array $data): array
    {
        $requestSource = config('tripwire.actions.request_source');
        $data['user_agent'] = $requestSource::getUserAgent();
        $data['robot_crawler'] = $requestSource::getRobot();
        $data['browser_fingerprint'] = $requestSource::getBrowserFingerprint();

        return $data;
    }

    function fingerprint(Request $request)
    {
        return HashService::create(json_encode([
            $request->url(),
            $request->method(),
            $request->ips(),
            $request->header(),
            $request->all()
        ]));
    }
}
