<?php

namespace Yormy\TripwireLaravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Yormy\TripwireLaravel\Mailables\AttackDetectedMailable;

class AttackDetected extends Notification implements ShouldQueue
{
    use Queueable;

    public $notifications;

    public function __construct(
        private readonly string $ipAddress,
        private readonly int $userId,
        private readonly string $userType,
        private readonly string $browserFingerprint,
    ) {
        $this->notifications = config('tripwire.notifications');
    }

    public function via($notifiable)
    {
        $channels = [];

        foreach ($this->notifications as $channel => $settings) {
            if (empty($settings['enabled'])) {
                continue;
            }

            $channels[] = $channel;
        }

        return $channels;
    }

    public function toMail($notifiable)
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
        $mail = new AttackDetectedMailable(
            title: $title,
            msg: $message,
            ipAddress: $this->ipAddress,
            userId: $this->userId,
        );
        $mail
            ->subject($subject)
            ->to($this->notifications['mail']['from'], $this->notifications['mail']['name']);

        return $mail;
    }
//
//    /**
//     * Get the Slack representation of the notification.
//     *
//     * @param  mixed  $notifiable
//     * @return SlackMessage
//     */
//    public function toSlack($notifiable)
//    {
//        $message = trans('firewall::notifications.slack.message', [
//            'domain' => request()->getHttpHost(),
//        ]);
//
//        return (new SlackMessage)
//            ->error()
//            ->from($this->notifications['slack']['from'], $this->notifications['slack']['emoji'])
//            ->to($this->notifications['slack']['channel'])
//            ->content($message)
//            ->attachment(function ($attachment) {
//                $attachment->fields([
//                    'IP' => $this->log->ip,
//                    'Type' => ucfirst($this->log->middleware),
//                    'User ID' => $this->log->user_id,
//                    'URL' => $this->log->url,
//                ]);
//            });
//    }
}
