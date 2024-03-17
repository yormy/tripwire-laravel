<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;

class UserBlockedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $title,
        public readonly string $msg,
        public readonly string $ipAddress,
        public readonly ?string $userId,
        public readonly string $url,
        private readonly NotificationMailConfig|NotificationSlackConfig $mailSettings,
    ) {
        // ...
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: $this->mailSettings->templateHtml,
            text: $this->mailSettings->templatePlain,
        );
    }

    /**
     * @return array<string>
     */
    public function attachments(): array
    {
        return [];
    }
}
