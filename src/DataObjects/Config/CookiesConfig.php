<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class CookiesConfig
{
    public string $browserFingerprint;

    private function __construct()
    {
    }

    public static function make(string $browserFingerprint): self
    {
        $object = new CookiesConfig();

        $object->browserFingerprint = $browserFingerprint;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new CookiesConfig();

        $object->browserFingerprint = $data['browser_fingerprint'];

        return $object;
    }

    public function toArray(): array
    {
        return [
            'browser_fingerprint' => $this->browserFingerprint,
        ];
    }
}
