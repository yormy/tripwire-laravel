<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Repositories;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\RequestSource;
use Illuminate\Support\Facades\Auth;

class LogRepository
{
    public function add()
    {
        $data = [
            'xid' => rand(0,99999),
            'ip' => rand(0,99999),
            'middleware' => rand(0,99999),
        ];

        $data = $this->addUser($data);
        $data = $this->addUserAgent($data);

        return TripwireLog::create($data);
    }

    private function addUser(array $data): array
    {
        $user = Auth::user();

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
}
