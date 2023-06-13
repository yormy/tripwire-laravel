<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class HtmlResponseConfig
{
    public ?int $code;

    public ?string $view;

    private function __construct()
    {}

    public static function make(
        ?int $code,
        ?string $view
    ): self
    {
        $object = new HtmlResponseConfig();

        $object->code = $code;
        $object->view = $view;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new HtmlResponseConfig();

        $object->code = $data['code'] ?? null;
        $object->view = $data['view'];

       return $object;
    }


    public function toArray(): array
    {
        $data = [];
        if ($this->code) {
            $data['code'] = $this->code;
        }

        if ($this->view) {
            $data['view'] = $this->view;
        }

        return $data;
    }
}
