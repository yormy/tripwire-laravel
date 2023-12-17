<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Members;

use Yormy\TripwireLaravel\Http\Controllers\Base\BaseBlockController;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

class MemberBlockController extends BaseBlockController
{
    public function getUser($userId)
    {
        return UserResolver::getMemberById($userId);
    }
}
