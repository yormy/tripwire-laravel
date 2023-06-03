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

        $logReferer = substr($request->headers->get('referer'), 0, config('tripwire.log.max_referer_size'));
        $data['referer'] = $logReferer;

        $logHeader = substr(json_encode($request->header()), 0, config('tripwire.log.max_header_size'));
        $data['header'] = $logHeader;

        $logRequest = substr(json_encode($request->all()), 0, config('tripwire.log.max_request_size'));
        $data['request'] = $logRequest;
        $data['request_fingerprint'] = $this->fingerprint($request);

        return $data;
    }


    private function addUser(Request $request, array $data): array
    {
        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request);
        $userType = $userClass::getType($request);

        $data['user_id'] = $userId ?? null;
        $data['user_type'] = $userType  ?? null;

        return $data;
    }

    private function addUserAgent(array $data): array
    {
        $requestSourceClass = config('tripwire.services.request_source');

        $userAgent = substr(json_encode($requestSourceClass::getUserAgent()), 0, 190);
        $data['user_agent'] = $userAgent;
        $data['robot_crawler'] = $requestSourceClass::getRobot();
        $data['browser_fingerprint'] = $requestSourceClass::getBrowserFingerprint();

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
