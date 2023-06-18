<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

abstract class OnlyExceptConfig
{
    public array $only;

    public array $except;

    public $model = MissingPageConfig::class;

    private function __construct()
    {
    }

    public static function make(
        array $only = [],
        array $except = []
    ): self {
        $model = static::MODEL;
        $object = new $model();

        $object->only = $only;
        $object->except = $except;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $model = static::MODEL;
        $object = new $model();

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
