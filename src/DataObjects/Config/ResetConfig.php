<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ResetConfig
{
    public bool $enabled;

    public bool $softDelete;

    public int $linkExpireMintues;

    private function __construct()
    {}

    public static function make(
        bool $enabled,
        bool $softDelete,
        int $linkExpireMintues,
    ): self
    {
        $object = new ResetConfig();

        $object->enabled = $enabled;
        $object->softDelete = $softDelete;
        $object->linkExpireMintues = $linkExpireMintues;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

       $object = new ResetConfig();

        $object->enabled = $data['enabled'];
        $object->softDelete = $data['soft_delete'];
        $object->linkExpireMintues = $data['link_expiry_minutes'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'soft_delete' => $this->softDelete,
            'link_expiry_minutes' => $this->linkExpireMintues,
        ];
    }
}
