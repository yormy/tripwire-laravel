<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

abstract class OnlyExceptConfig
{
    /**
     * @var array<string> $only
     */
    public array $only;

    /**
     * @var array<string> $except
     */
    public array $except;

    private function __construct()
    {
        // disable default constructor
    }

    /**
     * @param array<string> $only
     * @param array<string> $except
     */
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

    /**
     * @param array<string> $only
     */
    public function only(array $only): static
    {
        $this->only = $only;

        return $this;
    }

    /**
     * @param array<string> $except
     */
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
