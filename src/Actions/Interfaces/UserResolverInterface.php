<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Actions\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;

interface UserResolverInterface
{
    public static function getCurrent(): ?Authenticatable;

    public static function getRandom(): ?Authenticatable;

    public static function getMemberById(string|int $id): Authenticatable;

    public static function getAdminById(string|int $id): Authenticatable;

    public static function getMemberByXid(string|int $id): Authenticatable;

    public static function getAdminByXid(string|int $id): Authenticatable;

    public static function getMemberOnXId(string|int $xid): ?Authenticatable;
}
