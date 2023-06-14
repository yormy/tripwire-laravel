<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class JsonResponseConfig
{
    public int $code;

    public bool $abort;

    public ?array $json;

    public ?string $exception;

    public ?string $messageKey;

    private function __construct()
    {}

    // TODO: how to pass and handle missing parameters
    // like code = missing ipv 0, rename checker to wires.
    public static function make(
        ?int $code = 0,
        ?bool $abort = false,
        ?array $json = null,
        ?string $exception = null,
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

    public function messageKey(string $messageKey): self
    {
        $this->messageKey = $messageKey;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'abort' => $this->abort,
            'json' => $this->json,
            'exception' => $this->exception,
            'message_key' => $this->messageKey,
        ];
    }
}
