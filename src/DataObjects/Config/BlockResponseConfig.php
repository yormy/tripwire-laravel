<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class BlockResponseConfig
{
    public ?JsonResponseConfig $jsonResponse;

    public ?HtmlResponseConfig $htmlResponse;

    private function __construct()
    {
    }

    public static function make(
        ?JsonResponseConfig $jsonResponse = null,
        ?HtmlResponseConfig $htmlResponse = null
    ): self {
        $object = new BlockResponseConfig();

        $object->jsonResponse = $jsonResponse;
        $object->htmlResponse = $htmlResponse;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new BlockResponseConfig();

        if (isset($data['json'])) {
            $object->jsonResponse = JsonResponseConfig::makeFromArray($data['json']);
        }

        if (isset($data['html'])) {
            $object->htmlResponse = HtmlResponseConfig::makeFromArray($data['html']);
        }

        return $object;
    }

    public function json(JsonResponseConfig $jsonResponse): self
    {
        $this->jsonResponse = $jsonResponse;

        return $this;
    }

    public function html(HtmlResponseConfig $htmlResponse): self
    {
        $this->htmlResponse = $htmlResponse;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        if (isset($this->jsonResponse)) {
            $data['json'] = $this->jsonResponse->toArray();
        }

        if (isset($this->htmlResponse)) {
            $data['html'] = $this->htmlResponse->toArray();
        }

        return $data;
    }
}
