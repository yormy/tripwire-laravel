<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class UrlsConfig
{
    public array $only;

    public array $except;

    private function __construct()
    {}

    public static function make(
        array $only = [],
        array $except = []
    ): self
    {
        $object = new UrlsConfig();

        $object->only = $only;
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

    public function only(array $only): self
    {
        $this->only = $only;

        return $this;
    }

    public function except(array $except): self
    {
        $this->except = $except;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'only' => $this->only,
            'except' => $this->except,
        ];
    }
}
