<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class MissingModelConfig
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
        $object = new MissingModelConfig();

        $object->only = $only;
        $object->except = $except;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new MissingModelConfig();

        if (isset($data['except'])) {
            $object->except = $data['except'];
        }

        if (isset($data['only'])) {
            $object->only = $data['only'];
        }

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
        $data = [];

        if (isset($this->only)) {
            $data['only'] = $this->only;
        }

        if (isset($this->except)) {
            $data['except'] = $this->except;
        }

        return $data;
    }
}
