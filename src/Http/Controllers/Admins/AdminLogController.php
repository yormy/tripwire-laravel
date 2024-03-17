<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Admins;

use Yormy\TripwireLaravel\Http\Controllers\Base\BaseLogController;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

/**
 * @group Tripwire
 *
 * @subgroup Admin
 */
class AdminLogController extends BaseLogController
{
    public function getUser($userId)
    {
        return UserResolver::getAdminByXid($userId);
    }
}
