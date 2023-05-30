<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Akaunting\Firewall\Traits\Helper as FirewallHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LiranCo\NotificationSubscriptions\Events\NotificationSuppressed;
use Yormy\TripwireLaravel\Repositories\LogRepository;


class LogEvent extends BaseListener
{

    public function handle($event)
    {
        ray('event logevent');

        $logRepository = new LogRepository();
        $logRepository->add($this->request, $event);

//        $this->request = request();
//
//        $userId = 0;
//        $user = Auth::user();
//        if ($user) {
//            $userId = $user->id;
//        }
//
//        $this->log($event->getCode(), $userId, $event->getSeverity());
    }
}
