<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\Resolvers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Yormy\TripwireLaravel\Actions\Interfaces\UserResolverInterface;
use Yormy\TripwireLaravel\Tests\Setup\Models\Admin;
use Yormy\TripwireLaravel\Tests\Setup\Models\Member;

class UserResolver implements UserResolverInterface
{
    public static function getCurrent(): ?Authenticatable
    {
        return Auth::user();
    }

    public static function getRandom(): ?Authenticatable
    {
        return Member::first();
    }

    public static function getMemberById(string | int $id): Authenticatable
    {
        return Member::where('id', $id)->firstOrFail();
    }

    public static function getAdminById(string | int $id): Authenticatable
    {
        return Admin::where('id', $id)->firstOrFail();
    }

    public static function getMemberByXid(string | int $id): Authenticatable
    {
        return Member::where('xid', $id)->firstOrFail();
    }

    public static function getAdminByXid(string | int $id): Authenticatable
    {
        return Admin::where('xid', $id)->firstOrFail();
    }

    public static function getMemberOnXId(string | int $xid): ?Authenticatable
    {
        return Member::where('xid', $xid)->first();
    }
}
