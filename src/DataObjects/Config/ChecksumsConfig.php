<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ChecksumsConfig
{
    public ?string $posted;

    public ?string $timestamp;

    public ?string $serversideCalculated;

    private function __construct()
    {
    }

    public static function make(
        string $posted = null,
        string $timestamp = null,
        string $serversideCalculated = null,
    ): self {
        $object = new ChecksumsConfig();

        $object->posted = $posted;
        $object->timestamp = $timestamp;
        $object->serversideCalculated = $serversideCalculated;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new ChecksumsConfig();

        $object->posted = $data['posted'];
        $object->timestamp = $data['timestamp'];
        $object->serversideCalculated = $data['serverside_calculated'];

        return $object;
    }

    public function posted(string $posted): self
    {
        $this->posted = $posted;

        return $this;
    }

    public function timestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function serversideCalculated(string $serverSideCalculated): self
    {
        $this->serversideCalculated = $serverSideCalculated;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'posted' => $this->posted,
            'timestamp' => $this->timestamp,
            'serverside_calculated' => $this->serversideCalculated,
        ];
    }
}
