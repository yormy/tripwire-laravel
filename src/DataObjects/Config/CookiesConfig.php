<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class CookiesConfig
{
    public string $browserFingerprint;

    private function __construct()
    {
        // disable default constructor
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

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            'browser_fingerprint' => $this->browserFingerprint,
        ];
    }
}
