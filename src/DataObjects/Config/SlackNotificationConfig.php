<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

use function PHPUnit\Framework\isEmpty;

class SlackNotificationConfig
{
    public function __construct(
        public bool   $enabled,
        public string $from,
        public string $to,
        public string $emoji,
        public ?string $channel,
    )
    {
        if (!$channel) {
            throw new \Exception('Slack Channel missing');
        }

        if (empty($to)) {
            throw new \Exception('Slack to missing');
        }

    }

    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'from' => $this->from,
            'to' => $this->to,
            'emoji' => $this->emoji,
            'channel' => $this->channel,
        ];
    }
}
