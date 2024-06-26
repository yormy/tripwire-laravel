<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class WhitelistConfig
{
    /**
     * @var array<string>
     */
    public array $ips;

    private function __construct()
    {
        // disable default constructor
    }

    /**
     * @param  array<string>  $ips
     */
    public static function make(array $ips): self
    {
        $object = new WhitelistConfig();

        $object->ips = $ips;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new WhitelistConfig();

        $object->ips = $data['ips'];

        return $object;
    }

    /**
     * @return array<string, array<string>>
     */
    public function toArray(): array
    {
        return [
            'ips' => $this->ips,
        ];
    }
}
