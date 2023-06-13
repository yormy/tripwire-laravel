<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class MailNotificationConfig
{
    public function __construct(
        public bool   $enabled,
        public string $name,
        public string $from,
        public string $to,
        public string $template,
        public string $templatePlain,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'name' => $this->name,
            'from' => $this->from,
            'to' => $this->to,
            'template' => $this->template,
            'temmplatePlain' => $this->templatePlain,
        ];
    }
}
