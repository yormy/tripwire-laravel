<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Notifications;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail(): ?string
    {
        return config('tripwire.notifications.mail.to');
    }

    public function routeNotificationForSlack(): ?string
    {
        return config('tripwire.notifications.slack.to');
    }

    public function getKey(): int
    {
        return 1;
    }
}
