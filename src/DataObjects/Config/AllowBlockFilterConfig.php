<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class AllowBlockFilterConfig
{
    /**
     * @var array<string> $allow
     */
    public array $allow;

    /**
     * @var array<string> $block
     */
    public array $block;

    private function __construct()
    {
        // disable default constructor
    }

    /**
     * @param array<string> $allow
     * @param array<string> $block
     */
    public static function make(
        array $allow = [],
        array $block = []
    ): static {
        $object = new AllowBlockFilterConfig();

        $object->allow = $allow;
        $object->block = $block;

        return $object;
    }

    /**
     * @param array<string>| null $data
     */
    public static function makeFromArray(?array $data): ?static
    {
        if (! $data) {
            return null;
        }

        $object = new AllowBlockFilterConfig();

        if (isset($data['block'])) {
            $object->except = $data['block'];
        }

        if (isset($data['allow'])) {
            $object->only = $data['allow'];
        }

        return $object;
    }

    /**
     * @param array<string> $allow
     */
    public function allow(array $allow): static
    {
        $this->allow = $allow;

        return $this;
    }

    /**
     * @param array<string> $block
     */
    public function block(array $block): static
    {
        $this->block = $block;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        if (isset($this->allow)) {
            $data['allow'] = $this->allow;
        }

        if (isset($this->block)) {
            $data['block'] = $this->block;
        }

        return $data;
    }
}
