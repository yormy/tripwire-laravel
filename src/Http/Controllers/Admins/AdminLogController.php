<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Admins;

use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\Http\Controllers\Base\BaseLogController;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

/**
 * @group Tripwire
 *
 * @subgroup Admin Log
 */
class AdminLogController extends BaseLogController
{
    public function getUser($userId)
    {
        return UserResolver::getAdminByXid($userId);
    }
}
