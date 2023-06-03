<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Illuminate\Auth\Events\Login;
//use Akaunting\Firewall\Events\AttackDetected;
//use Akaunting\Firewall\Traits\Helper;
//use Illuminate\Auth\Events\Failed as Event;
//use function Akaunting\Firewall\Listeners\event;
//use function Akaunting\Firewall\Listeners\request;

class LoginFailedListener
{
    public function handle($event): void
    {
        ray('failed Login event in tripwire');
//        $this->request = request();
//        $this->middleware = 'login';
//        $this->user_id = 0;
//
//        if ($this->skip($event)) {
//            return;
//        }
//
//        $this->request['password'] = '******';
//
//        $log = $this->log();
//
//        event(new AttackDetected($log));
    }

    public function skip($event): bool
    {
        if ($this->isDisabled()) {
            return true;
        }

        if ($this->isWhitelist()) {
            return true;
        }

        return false;
    }
}
