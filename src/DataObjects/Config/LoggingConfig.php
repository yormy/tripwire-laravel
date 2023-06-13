<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class LoggingConfig
{
    public int $maxRequestSize;
    public int $maxHeaderSize;
    public int $maxRefererSize;

    public array $remove;

    private function __construct()
    {
        // ...
    }

    public static function make(
        string $maxRequestSize,
        string $maxHeaderSize,
        string $maxRefererSize,
        array $remove
    ): self
    {
        $object = new LoggingConfig();

        $object->maxRequestSize = $maxRequestSize;
        $object->maxHeaderSize = $maxHeaderSize;
        $object->maxRefererSize = $maxRefererSize;
        $object->remove = $remove;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new LoggingConfig();

        $object->maxRequestSize = $data['max_request_size'];
        $object->maxHeaderSize = $data['max_header_size'];
        $object->maxRefererSize = $data['max_referer_size'];
        $object->remove = $data['remove'];

        return $object;
    }


    public function toArray(): array
    {
        return [
            'max_request_size' => $this->maxRequestSize,
            'max_header_size' => $this->maxHeaderSize,
            'max_referer_size' => $this->maxRefererSize,
            'remove' => $this->remove,
        ];
    }
}
