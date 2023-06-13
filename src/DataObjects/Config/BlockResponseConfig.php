<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class BlockResponseConfig
{
    public  JsonResponseConfig $jsonResponse;

    public  HtmlResponseConfig $htmlResponse;

    private function __construct()
    {}

    public static function make(
        JsonResponseConfig $jsonResponse,
        HtmlResponseConfig $htmlResponse
    ): self
    {
        $object = new BlockResponseConfig();

        $object->jsonResponse = $jsonResponse;
        $object->htmlResponse = $htmlResponse;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new BlockResponseConfig();

        $object->jsonResponse = JsonResponseConfig::makeFromArray($data['json']);
        $object->htmlResponse = HtmlResponseConfig::makeFromArray($data['html']);

       return $object;
    }


    public function toArray(): array
    {
        return [
            'json' => $this->jsonResponse->toArray(),
            'html' => $this->htmlResponse->toArray(),
        ];
    }
}
