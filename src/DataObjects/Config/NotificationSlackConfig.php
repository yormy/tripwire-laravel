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
    {
    }

    public static function make(
        bool $enabled,
        string $from = '',
        string $to = '',
        string $emoji = '',
        string $channel = '',
    ): self {
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
        if (! $data) {
            return null;
        }

        $object = new NotificationSlackConfig();

        $object->enabled = $data['enabled'];
        $object->from = $data['from'];
        $object->to = $data['to'];

        if (isset($data['emoji'])) {
            $object->emoji = $data['emoji'];
        }

        $object->channel = $data['channel'];

        return $object;
    }

    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function emoji(string $emoji): self
    {
        $this->emoji = $emoji;

        return $this;
    }

    public function channel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function toArray(): array
    {
        if (! $this->enabled) {
            return [];
        }

        if (! $this->channel) {
            throw new \Exception('Slack Channel missing');
        }

        if (empty($this->to)) {
            throw new \Exception('Slack to missing');
        }

        return [
            'enabled' => $this->enabled,
            'from' => $this->from,
            'to' => $this->to,
            'emoji' => $this->emoji,
            'channel' => $this->channel,
        ];
    }
}
