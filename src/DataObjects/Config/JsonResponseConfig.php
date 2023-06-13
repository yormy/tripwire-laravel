<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class JsonResponseConfig
{
    public int $code;

    public bool $abort;

    private function __construct()
    {}

    public static function make(
        int $code,
        bool $abort
    ): self
    {
        $object = new JsonResponseConfig();

        $object->code = $code;
        $object->abort = $abort;

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

       return $object;
    }


    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'abort' => $this->abort,
        ];
    }
}
