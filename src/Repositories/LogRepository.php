<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Observers\Interfaces\LoggableEventInterface;
use Yormy\TripwireLaravel\Services\HashService;
use Yormy\TripwireLaravel\Services\RequestSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
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

    public function scoreViolationsByIp(int $withinMinutes, array $violations, string $ipAddress): int
    {
        // current user? current ip // current browserfinger
        return (int)$this->queryScoreViolations($withinMinutes, $violations)
            ->byIp($ipAddress)
            ->sum('event_score');
    }

    public function scoreViolationsByUser(int $withinMinutes, array $violations, $userId, $userType): int
    {
        // current user? current ip // current browserfinger
        return (int)$this->queryScoreViolations($withinMinutes, $violations)
            ->byUserId($userId)
            ->byUserType($userType)
            ->sum('event_score');
    }

    public function scoreViolationsByBrowser(int $withinMinutes, array $violations, string $browserFingerprint): int
    {
        // current user? current ip // current browserfinger
        return (int)$this->queryScoreViolations($withinMinutes, $violations)
            ->byBrowser($browserFingerprint)
            ->sum('event_score');
    }

    private function queryScoreViolations(int $withinMinutes, array $violations)
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
        $requestSource = new RequestSource();
        $data['user_agent'] = $requestSource->getUserAgent();
        $data['robot_crawler'] = $requestSource->getRobot();
        $data['browser_fingerprint'] = $requestSource->getBrowserFingerprint();

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
