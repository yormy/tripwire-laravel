<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class HtmlResponseConfig
{
    public ?int $code;

    public ?string $view;

    public ?string $exception;

    public ?string $redirectUrl;

    public ?string $messageKey;

    private function __construct()
    {}

    public static function make(
        ?int $code = 0,
        ?string $view = null,
        ?string $exception = null,
        ?string $redirectUrl = null,
        ?string $messageKey = null
    ): self
    {
        $object = new HtmlResponseConfig();

        $object->code = $code;
        $object->view = $view;
        $object->exception = $exception;
        $object->redirectUrl = $redirectUrl;
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
        $object->redirectUrl = $data['redirect_url'] ?? null;
        $object->messageKey = $data['message_key'] ?? null;

       return $object;
    }


    public function code(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function exception(string $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    public function redirectUrl(string $redirectUrl): self
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }


    public function messageKey(string $messageKey): self
    {
        $this->messageKey = $messageKey;

        return $this;
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

        if ($this->redirectUrl) {
            $data['redirect_url'] = $this->redirectUrl;
        }

        if ($this->messageKey) {
            $data['message_key'] = $this->messageKey;
        }

        return $data;
    }
}
