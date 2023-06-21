<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Throwable;
use Yormy\TripwireLaravel\Notifications\Notifiable;
use Yormy\TripwireLaravel\Notifications\UserBlockedNotification;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;

class NotifyAdmin
{
    public function handle(TripwireBlockedEvent $event): void
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
