<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ConfigDatetimeOption
{
    public function __construct(public string $format, public int $offset = 0)
    {
    }

    public function toArray(): array
    {
        return [
            'format' => $this->format,
            'offset' => $this->offset
        ];
    }
}
