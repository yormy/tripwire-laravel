<?php

namespace Yormy\TripwireLaravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\Mailables\UserBlockedMailable;

class UserBlockedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $notifications;

    public function __construct(
        private readonly string $ipAddress,
        private readonly ?int $userId,
        private readonly ?string $userType,
        private readonly string $browserFingerprint,
        private readonly NotificationMailConfig| NotificationSlackConfig $settings,
    ) {
        $this->notifications = config('tripwire.notifications');
    }

    /**
     * @psalm-return list<mixed>
     */
    public function via($notifiable): array
    {
        $channels = [];

        foreach ($this->notifications as $channel => $settings) {

            foreach($settings as $config) {
                if ($config['enabled']) {
                    $channels[] = $channel;
                    continue;
                }
            }
        }

        return $channels;
    }

    public function toMail($notifiable): UserBlockedMailable
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
        $mail = new UserBlockedMailable(
            title: $title,
            msg: $message,
            ipAddress: $this->ipAddress,
            userId: $this->userId,
        );

        $config = ConfigBuilder::fromArray(config('tripwire'));
        $mailSettings = $config->notificationsMail[0];

        $mail
            ->subject($subject)
            ->to($mailSettings->from, $mailSettings->to);

        return $mail;
    }

    public function toSlack($notifiable)
    {
        $domain = request()->getHttpHost();

        $message = __('tripwire::notifications.slack.message', [
            'domain' => $domain,
        ]);

        return (new SlackMessage)
            ->error()
            ->from($this->notifications['slack']['from'], $this->notifications['slack']['emoji'])
            ->to($this->notifications['slack']['channel'])
            ->content($message)
            ->attachment(function ($attachment) use ($domain) {
                $attachment->fields([
                    'IP' => $this->ipAddress,
                    'User ID' => $this->userId,
                    'domain' => $domain,
                ]);
            });
    }
}
