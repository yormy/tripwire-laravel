<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ChecksumsConfig
{
    public string $posted;
    public string $timestamp;
    public string $serversideCalculated;

    private function __construct()
    {}

    public static function make(
        string $posted,
        string $timestamp,
        string $serversideCalculated,
    ): self
    {
        $object = new ChecksumsConfig();

        $object->posted = $posted;
        $object->timestamp = $timestamp;
        $object->serversideCalculated = $serversideCalculated;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new ChecksumsConfig();

        $object->posted = $data['posted'];
        $object->timestamp = $data['timestamp'];
        $object->serversideCalculated = $data['serverside_calculated'];

        return $object;
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
