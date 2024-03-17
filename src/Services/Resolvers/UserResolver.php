<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\Resolvers;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockUsersv2\Domain\User\Models\Admin;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;

class UserResolver
{
    public static function getCurrent(): Member | Admin | null
    {
        return Auth::user();
    }

    public static function getRandom(): Member
    {
        return Member::first();
    }

    public static function getMemberById(string | int $id): Member
    {
        return Member::where('id', $id)->firstOrFail();
    }

    public static function getAdminById(string | int $id): Admin
    {
        return Admin::where('id', $id)->firstOrFail();
    }

    public static function getMemberByXid(string | int $id): Member
    {
        return Member::where('xid', $id)->firstOrFail();
    }

    public static function getAdminByXid(string | int $id): Admin
    {
        return Admin::where('xid', $id)->firstOrFail();
    }

    public static function getMemberOnXId(string | int $xid): ?Member
    {
        return Member::where('xid', $xid)->first();
    }
}
