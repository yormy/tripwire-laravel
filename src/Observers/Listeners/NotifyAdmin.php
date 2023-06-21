<?php

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Throwable;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\Notifications\Notifiable;
use Yormy\TripwireLaravel\Notifications\UserBlockedNotification;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;

class NotifyAdmin
{
    public function handle(TripwireBlockedEvent $event): void
    {
        $config = ConfigBuilder::fromArray(config('tripwire'));

        foreach ($config->notificationsMail as $mailSettings) {
            if ($mailSettings->enabled) {
                $message = new UserBlockedNotification(
                    $event->ipAddress,
                    $event->userId,
                    $event->userType,
                    $event->browserFingerprint,
                    $mailSettings
                );

                (new Notifiable)->notify($message);
            }
        }

        foreach ($config->notificationsSlack as $mailSettings) {
            if ($mailSettings->enabled) {
                $message = new UserBlockedNotification(
                    $event->ipAddress,
                    $event->userId,
                    $event->userType,
                    $event->browserFingerprint,
                    $mailSettings
                );

                (new Notifiable)->notify($message);
            }
        }
    }
}
