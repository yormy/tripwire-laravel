<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Illuminate\Auth\Events\Login;

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
