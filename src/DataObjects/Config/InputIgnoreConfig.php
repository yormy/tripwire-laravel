<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class InputIgnoreConfig
{
    public array $input;
    public array $cookies;

    public array $header;

    private function __construct()
    {}

    public static function make(
        array $input,
        array $cookies,
        array $header
    ): self
    {
        $object = new InputIgnoreConfig();

        $object->input = $input;
        $object->cookies = $cookies;
        $object->header = $header;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new InputIgnoreConfig();

        $object->input = $data['input'];
        $object->cookies = $data['cookies'];
        $object->header = $data['header'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'input' => $this->input,
            'cookies' => $this->cookies,
            'header' => $this->header,
        ];
    }
}
