<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Notifications;

use Illuminate\Auth\Authenticatable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;
use Yormy\TripwireLaravel\Mailables\UserBlockedMailable;
use Yormy\TripwireLaravel\Notifications\Notifiable;

class UserBlockedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var array<string> $notifications
     */
    public array $notifications;

    public function __construct(
        private readonly string $ipAddress,
        private readonly ?int $userId,
        private readonly ?string $userType,
        private readonly string $browserFingerprint,
        private readonly NotificationMailConfig|NotificationSlackConfig $settings,
    ) {
        $this->notifications = config('tripwire.notifications');
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function via(Notifiable $notifiable): array
    {
        $channels = [];

        foreach ($this->notifications as $channel => $settings) {
            foreach ($settings as $config) {
                if (isset($config['enabled'])) {
                    $channels[] = $channel;

                    continue;
                }
            }
        }

        return $channels;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toMail(Notifiable $notifiable): UserBlockedMailable
    {
        $domain = request()->getHttpHost();

        $subject = __('tripwire::notifications.mail.subject', [
            'domain' => $domain,
        ]);

        $message = __('tripwire::notifications.mail.message', [
            'domain' => $domain,
            'ip' => $this->ipAddress,
        ]);

        $title = $subject;
        $mailSettings = $this->settings;
        $mail = new UserBlockedMailable(
            title: $title,
            msg: $message,
            ipAddress: $this->ipAddress,
            userId: $this->userId,
            url: '',
            mailSettings: $mailSettings
        );

        $mail
            ->subject($subject)
            ->to($mailSettings->from, $mailSettings->to);

        return $mail;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toSlack(Notifiable $notifiable)
    {
        $domain = request()->getHttpHost();

        $message = __('tripwire::notifications.slack.message', [
            'domain' => $domain,
        ]);

        $mailSettings = $this->settings;

        return (new SlackMessage())
            ->error()
            ->from($mailSettings['from'], $mailSettings['emoji'])
            ->to($mailSettings['channel'])
            ->content($message)
            ->attachment(function ($attachment) use ($domain): void {
                $attachment->fields([
                    'IP' => $this->ipAddress,
                    'User ID' => $this->userId,
                    'domain' => $domain,
                ]);
            });
    }
}
