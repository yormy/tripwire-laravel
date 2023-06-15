<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class JsonResponseConfig
{
    public int $code;

    public bool $abort;

    public ?array $json;

    public ?string $exception;

    public ?string $redirectUrl;

    public ?string $messageKey;

    private function __construct()
    {}

    public static function make(
        ?int $code = 0,
        ?bool $abort = false,
        ?array $json = null,
        ?string $exception = null,
        ?string $redirectUrl = null,
        ?string $messageKey = null
    ): self
    {
        $object = new JsonResponseConfig();

        if (isset($code)) {
            $object->code = $code;
        }

        if (isset($abort)) {
            $object->abort = $abort;
        }

        $object->json = $json;
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

       $object = new JsonResponseConfig();

        $object->code = $data['code'];
        $object->abort = $data['abort'];
        $object->json = $data['json'];
        $object->exception = $data['exception'];
        $object->redirectUrl = $data['redirect_url'];
        $object->messageKey = $data['message_key'];

       return $object;
    }

    public function code(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function abort(bool $abort): self
    {
        $this->abort = $abort;

        return $this;
    }

    public function json(array $json): self
    {
        $this->json = $json;

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

        if ($this->abort) {
            $data['abort'] = $this->abort;
        }

        if ($this->json) {
            $data['json'] = $this->json;
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
