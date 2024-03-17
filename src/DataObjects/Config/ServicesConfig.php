<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ServicesConfig
{
    public string $requestSource;

    public string $user;

    public string $ipAddress;

    private function __construct()
    {
        // ...
    }

    public static function make(
        string $requestSource,
        string $user,
        string $ipAddress,
    ): self {
        $object = new ServicesConfig();

        $object->requestSource = $requestSource;
        $object->user = $user;
        $object->ipAddress = $ipAddress;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new ServicesConfig();

        $object->requestSource = $data['request_source'];
        $object->user = $data['user'];
        $object->ipAddress = $data['ip_address'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'request_source' => $this->requestSource,
            'user' => $this->user,
            'ip_address' => $this->ipAddress,
        ];
    }
}
