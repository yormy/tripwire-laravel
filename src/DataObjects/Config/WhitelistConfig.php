<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class WhitelistConfig
{
    public array $ips;

    private function __construct()
    {}

    public static function make(array $ips): self
    {
        $object = new WhitelistConfig();

        $object->ips = $ips;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new WhitelistConfig();

        $object->ips = $data['ips'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'ips' => $this->ips
        ];
    }
}
