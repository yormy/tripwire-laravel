<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\Resolvers;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;

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

    public static function getMemberById($userId)
    {
        return Member::where('xid', $userId)->first();
    }

    public static function getMemberOnXId(string $xid): ?Member
    {
        return Member::where('xid', $xid)->first();
    }
}
