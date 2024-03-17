<?php

declare(strict_types=1);

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
    {
        // disable default constructor
    }

    public static function make(
        bool $enabled,
        string $name = '',
        string $from = '',
        string $to = '',
        string $templateHtml = '',
        ?string $templatePlain = '',
    ): self {
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
        if (! $data) {
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

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function templateHtml(string $templateHtml): self
    {
        $this->templateHtml = $templateHtml;

        return $this;
    }

    public function templatePlain(string $templatePlain): self
    {
        $this->templatePlain = $templatePlain;

        return $this;
    }

    public function toArray(): array
    {
        if (! $this->enabled) {
            return [];
        }

        if (empty($this->to)) {
            throw new \Exception('Mail to missing');
        }

        if (empty($this->from)) {
            throw new \Exception('Mail from missing');
        }

        if (empty($this->templateHtml) || empty($this->templatePlain)) {
            throw new \Exception('Mail template missing, either proved a HTML or PLAIN template');
        }

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
