<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ResetConfig
{
    public bool $enabled;

    public bool $softDelete;

    public int $linkExpireMintues;

    private function __construct()
    {
        // disable default constructor
    }

    public static function make(
        bool $enabled,
        bool $softDelete = false,
        int $linkExpireMintues = 60 * 24 * 3,
    ): self {
        $object = new ResetConfig();

        $object->enabled = $enabled;
        $object->softDelete = $softDelete;
        $object->linkExpireMintues = $linkExpireMintues;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new ResetConfig();

        $object->enabled = $data['enabled'];
        $object->softDelete = $data['soft_delete'];
        $object->linkExpireMintues = $data['link_expiry_minutes'];

        return $object;
    }

    public function softDelete(bool $softDelete): self
    {
        $this->softDelete = $softDelete;

        return $this;
    }

    public function linkExpireMinutes(int $linkExpireMintues): self
    {
        $this->linkExpireMintues = $linkExpireMintues;

        return $this;
    }

    /**
     * @return array<string, bool|int>
     */
    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'soft_delete' => $this->softDelete,
            'link_expiry_minutes' => $this->linkExpireMintues,
        ];
    }
}
