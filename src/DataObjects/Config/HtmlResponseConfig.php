<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class HtmlResponseConfig
{
    public ?int $code;

    public ?string $view;

    public ?string $exception;

    public ?string $messageKey;

    private function __construct()
    {}

    public static function make(
        ?int $code,
        ?string $view,
        ?string $exception = null,
        ?string $messageKey = null
    ): self
    {
        $object = new HtmlResponseConfig();

        $object->code = $code;
        $object->view = $view;
        $object->exception = $exception;
        $object->messageKey = $messageKey;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new HtmlResponseConfig();

        $object->code = $data['code'] ?? null;
        $object->view = $data['view'];
        $object->exception = $data['exception'] ?? null;
        $object->messageKey = $data['message_key'] ?? null;

       return $object;
    }


    public function toArray(): array
    {
        $data = [];
        if ($this->code) {
            $data['code'] = $this->code;
        }

        if ($this->view) {
            $data['view'] = $this->view;
        }

        if ($this->exception) {
            $data['exception'] = $this->exception;
        }

        if ($this->messageKey) {
            $data['message_key'] = $this->messageKey;
        }
        return $data;
    }
}
