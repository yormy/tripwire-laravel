<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class DatetimeConfig
{
    public string $format;

    public int $offset;

    private function __construct()
    {
        // disable default constructor
    }

    public static function make(
        string $format,
        int $offset = 0,
    ): self {
        $object = new DatetimeConfig();

        $object->format = $format;
        $object->offset = $offset;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new DatetimeConfig();

        $object->format = $data['format'];
        $object->offset = $data['offset'];

        return $object;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            'format' => $this->format,
            'offset' => $this->offset,
        ];
    }
}
