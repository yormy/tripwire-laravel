<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class NotificationMailConfig
{
    public bool $enabled;

    public string $name;

    public string $from;

    public string $to;
    public string $templateHtml;
    public ?string $templatePlain;

    private function __construct()
    {}

    public static function make(
        bool $enabled,
        string $name,
        string $from,
        string $to,
        string $templateHtml,
        ?string $templatePlain,
    ): self
    {
        $object = new NotificationMailConfig();

        $object->enabled = $enabled;
        $object->name = $name;
        $object->from = $from;
        $object->to = $to;
        $object->templateHtml = $templateHtml;
        $object->templatePlain = $templatePlain;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new NotificationMailConfig();

        $object->enabled = $data['enabled'];
        $object->name = $data['name'];
        $object->from = $data['from'];
        $object->to = $data['to'];
        $object->templateHtml = $data['template_html'];
        $object->templatePlain = $data['template_plain'] ?? null;

        return $object;
    }

    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'name' => $this->name,
            'from' => $this->from,
            'to' => $this->to,
            'template_html' => $this->templateHtml,
            'temmplate_plain' => $this->templatePlain,
        ];
    }
}
