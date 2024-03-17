<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class InputIgnoreConfig
{
    public array $inputs;

    public array $cookies;

    public array $header;

    /**
     * @param array<string> $inputs
     * @param array<string> $cookies
     * @param array<string> $header
     */
    public static function make(
        array $inputs = [],
        array $cookies = [],
        array $header = []
    ): self {
        $object = new InputIgnoreConfig();

        $object->inputs = $inputs;
        $object->cookies = $cookies;
        $object->header = $header;

        return $object;
    }

    /**
     * @param array<string> $data
     */
    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new InputIgnoreConfig();

        if (isset($data['inputs'])) {
            $object->inputs = $data['inputs'];
        }

        if (isset($data['cookies'])) {
            $object->cookies = $data['cookies'];
        }

        if (isset($data['header'])) {
            $object->header = $data['header'];
        }

        return $object;
    }

    /**
     * @param array<string> $inputs
     */
    public function inputs(array $inputs): self
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * @param array<string> $cookies
     */
    public function cookies(array $cookies): self
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * @param array<string> $headers
     */
    public function headers(array $headers): self
    {
        $this->header = $headers;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        if (isset($this->inputs)) {
            $data['inputs'] = $this->inputs;
        }

        if (isset($this->cookies)) {
            $data['cookies'] = $this->cookies;
        }

        if (isset($this->header)) {
            $data['header'] = $this->header;
        }

        return $data;
    }
}
