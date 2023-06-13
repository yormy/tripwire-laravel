<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class CookiesConfig
{
    public function __construct(
        public string $browserFingerprint,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'browser_fingerprint' => $this->browserFingerprint,
        ];
    }
}
