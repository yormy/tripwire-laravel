<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Admins;

use Yormy\TripwireLaravel\Http\Controllers\Base\BaseBlockController;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

/**
 * @group Tripwire
 *
 * @subgroup Admin Block
 */
class AdminBlockController extends BaseBlockController
{
    public function getUser($userId)
    {
        return UserResolver::getAdminByXid($userId);
    }
}
