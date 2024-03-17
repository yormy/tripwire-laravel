<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class JsonResponseConfig
{
    public int $code;

    /**
     * @var array<string>|null $json
     */
    public ?array $json;

    public ?string $exception;

    public ?string $redirectUrl;

    public ?string $messageKey;

    private function __construct()
    {
        // disable default constructor
    }

    public static function make(
        ?int $code = 406,
        ?array $json = null,
        ?string $exception = null,
        ?string $redirectUrl = null,
        ?string $messageKey = null
    ): self {
        $object = new JsonResponseConfig();

        if (isset($code)) {
            $object->code = $code;
        }

        $object->json = $json;
        $object->exception = $exception;
        $object->redirectUrl = $redirectUrl;

        $object->messageKey = $messageKey;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new JsonResponseConfig();

        if (isset($data['code'])) {
            $object->code = $data['code'];
        }

        if (isset($data['json'])) {
            $object->json = $data['json'];
        }

        if (isset($data['exception'])) {
            $object->exception = $data['exception'];
        }

        if (isset($data['redirect_url'])) {
            $object->redirectUrl = $data['redirect_url'];
        }

        if (isset($data['message_key'])) {
            $object->messageKey = $data['message_key'];
        }

        return $object;
    }

    public function code(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param array<string> $json
     */
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
        if (isset($this->code)) {
            $data['code'] = $this->code;
        }

        if (isset($this->json)) {
            $data['json'] = $this->json;
        }

        if (isset($this->exception)) {
            $data['exception'] = $this->exception;
        }

        if (isset($this->redirectUrl)) {
            $data['redirect_url'] = $this->redirectUrl;
        }

        if (isset($this->messageKey)) {
            $data['message_key'] = $this->messageKey;
        }

        return $data;
    }
}
