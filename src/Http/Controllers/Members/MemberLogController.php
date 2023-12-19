<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Members;

use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\Http\Controllers\Base\BaseLogController;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

class MemberLogController extends BaseLogController
{
    public function getUser($userId)
    {
        return UserResolver::getMemberByXid($userId);
    }
}
