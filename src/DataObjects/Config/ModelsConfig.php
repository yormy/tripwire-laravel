<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class ModelsConfig
{
    public string $log;

    public string $block;

    public string $member;

    public string $admin;

    private function __construct()
    {
        // disable default constructor
    }

    public static function make(
        string $log,
        string $block,
        string $member,
        string $admin,
    ): self {
        $object = new ModelsConfig();

        $object->log = $log;
        $object->block = $block;
        $object->member = $member;
        $object->admin = $admin;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new ModelsConfig();

        $object->log = $data['log'];
        $object->block = $data['block'];
        $object->member = $data['member'];
        $object->admin = $data['admin'];

        return $object;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            'log' => $this->log,
            'block' => $this->block,
            'member' => $this->member,
            'admin' => $this->admin,
        ];
    }
}
