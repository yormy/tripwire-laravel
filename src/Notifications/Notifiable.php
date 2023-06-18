<?php

namespace Yormy\TripwireLaravel\Notifications;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail()
    {
        return config('tripwire.notifications.mail.to');
    }

    public function routeNotificationForSlack()
    {
        return config('tripwire.notifications.slack.to');
    }

    public function getKey(): int
    {
        return 1;
    }
}
