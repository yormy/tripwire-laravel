<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\Resolvers;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;
use Mexion\BedrockUsersv2\Domain\User\Models\Admin;

class UserResolver
{
    public static function getCurrent() : ?User
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        return $user;
    }

    public static function getRandom()
    {
        return Member::first();
    }

    public static function getMemberByXid($id): Member
    {
        return Member::where('xid', $id)->firstOrFail();
    }

    public static function getAdminByXid($id): Admin
    {
        return Admin::where('xid', $id)->firstOrFail();
    }


    public static function getMemberOnXId(string $xid): ?Member
    {
        return Member::where('xid', $xid)->first();
    }
}
