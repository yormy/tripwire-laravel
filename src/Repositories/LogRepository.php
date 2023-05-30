<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Services\HashService;
use Yormy\TripwireLaravel\Services\RequestSource;
use Illuminate\Support\Facades\Auth;

class LogRepository
{
    public function add(Request $request)
    {
        $data['middleware'] = rand(0,99999);



        $model = config('tripwire.models.log');
        return $model::create($data);
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
        $user = $request->user();

        if ($user) {
            $data['user_id'] = $user->id;
            $data['user_type'] = get_class($user);
        }

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
