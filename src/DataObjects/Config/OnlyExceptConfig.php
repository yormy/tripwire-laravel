<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

abstract class OnlyExceptConfig
{
    public array $only;

    public array $except;

    protected function __construct()
    {
        // Only named constructors
    }

    public static function make(
        array $only = [],
        array $except = []
    ): static {
        $model = static::MODEL;
        $object = new $model();

        $object->only = $only;
        $object->except = $except;

        return $object;
    }

    public static function makeFromArray(?array $data): ?static
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

    public function only(array $only): static
    {
        $this->only = $only;

        return $this;
    }

    public function except(array $except): static
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
