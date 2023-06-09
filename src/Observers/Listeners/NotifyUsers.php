<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Throwable;
use Yormy\TripwireLaravel\Notifications\UserBlockedNotification;
use Yormy\TripwireLaravel\Notifications\Notifiable;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;

class NotifyUsers
{
    public function handle(TripwireBlockedEvent $event)
    {
        $message = new UserBlockedNotification(
            $event->ipAddress,
            $event->userId,
            $event->userType,
            $event->browserFingerprint,
        );

        try {
            (new Notifiable)->notify($message);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
