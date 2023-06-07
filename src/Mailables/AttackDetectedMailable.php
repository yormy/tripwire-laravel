<?php

namespace Yormy\TripwireLaravel\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttackDetectedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $title,
        public readonly string $msg,
        public readonly string $ipAddress = '',
        public readonly string $userId = '',
        public readonly string $url = '',
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
            view: config('tripwire.notifications.mail.template'),
            text: config('tripwire.notifications.mail.template_plain'),
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
