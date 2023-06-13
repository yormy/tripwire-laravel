<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class UrlsConfig
{
    public array $except;

    private function __construct()
    {}

    public static function make(array $except): self
    {
        $object = new UrlsConfig();

        $object->except = $except;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new UrlsConfig();

        $object->except = $data['except'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'except' => $this->except,
        ];
    }
}
