<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class LoggingConfig
{
    public int $maxRequestSize;

    public int $maxHeaderSize;

    public int $maxRefererSize;

    /**
     * @var array<string>
     */
    public array $remove;

    private function __construct()
    {
        // disable default constructor
    }

    /**
     * @param  array<string>  $remove
     */
    public static function make(
        int $maxRequestSize = 190,
        int $maxHeaderSize = 190,
        int $maxRefererSize = 190,
        array $remove = [],
    ): self {
        $object = new LoggingConfig();

        $object->maxRequestSize = $maxRequestSize;
        $object->maxHeaderSize = $maxHeaderSize;
        $object->maxRefererSize = $maxRefererSize;
        $object->remove = $remove;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new LoggingConfig();

        $object->maxRequestSize = $data['max_request_size'];
        $object->maxHeaderSize = $data['max_header_size'];
        $object->maxRefererSize = $data['max_referer_size'];
        $object->remove = $data['remove'];

        return $object;
    }

    public function maxRequestSize(int $maxHeaderSize): self
    {
        $this->maxHeaderSize = $maxHeaderSize;

        return $this;
    }

    public function maxHeaderSize(int $maxHeaderSize): self
    {
        $this->maxHeaderSize = $maxHeaderSize;

        return $this;
    }

    public function maxRefererSize(int $maxRefererSize): self
    {
        $this->maxRefererSize = $maxRefererSize;

        return $this;
    }

    /**
     * @param  array<string>  $remove
     */
    public function remove(array $remove): self
    {
        $this->remove = $remove;

        return $this;
    }

    /**
     * @return array<string, array<string>|int>
     */
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
