<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Controllers\Members;

use Yormy\TripwireLaravel\Http\Controllers\Base\BaseLogController;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

/**
 * @group Tripwire
 *
 * @subgroup Member
 */
class MemberLogController extends BaseLogController
{
    public function getUser(string | int $userId): mixed
    {
        return UserResolver::getMemberByXid($userId);
    }
}
