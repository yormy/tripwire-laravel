<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners;

use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\Notifications\Notifiable;
use Yormy\TripwireLaravel\Notifications\UserBlockedNotification;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;

class NotifyAdmin
{
    public function handle(TripwireBlockedEvent $event): void
    {
        $config = ConfigBuilder::fromArray(config('tripwire')); // @phpstan-ignore-line

        if (isset($config->notificationsMail)) {
            foreach ($config->notificationsMail as $mailSettings) {
                if (isset($mailSettings->enabled) && $mailSettings->enabled) { // @phpstan-ignore-line
                    $message = new UserBlockedNotification(
                        $event->ipAddress,
                        $event->userId,
                        $event->userType,
                        $event->browserFingerprint, // @phpstan-ignore-line
                        $mailSettings
                    );

                    (new Notifiable())->notify($message);
                }
            }
        }

        if (isset($config->notificationsSlack)) {
            foreach ($config->notificationsSlack as $mailSettings) {
                if (isset($mailSettings->enabled) && $mailSettings->enabled) { // @phpstan-ignore-line
                    $message = new UserBlockedNotification(
                        $event->ipAddress,
                        $event->userId,
                        $event->userType,
                        $event->browserFingerprint, // @phpstan-ignore-line
                        $mailSettings
                    );

                    (new Notifiable())->notify($message);
                }
            }
        }
    }
}
