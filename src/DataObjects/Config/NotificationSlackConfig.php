<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class NotificationSlackConfig
{
    public bool   $enabled;

    public string $from;

    public string $to;
    public string $emoji;
    public ?string $channel;

    private function __construct()
    {}

    public static function make(
        bool   $enabled,
        string $from,
        string $to,
        string $emoji,
        ?string $channel,
    ): self
    {
        if (!$channel) {
            throw new \Exception('Slack Channel missing');
        }

        if (empty($to)) {
            throw new \Exception('Slack to missing');
        }

        $object = new NotificationSlackConfig();

        $object->enabled = $enabled;
        $object->from = $from;
        $object->to = $to;
        $object->emoji = $emoji;
        $object->channel = $channel;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new NotificationSlackConfig();

        $object->enabled = $data['enabled'];
        $object->from = $data['from'];
        $object->to = $data['to'];
        $object->emoji = $data['emoji'];
        $object->channel = $data['channel'];

        return $object;
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
