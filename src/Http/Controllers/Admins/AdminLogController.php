<?php

declare(strict_types=1);

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
    public function getUser(string|int $userId): mixed
    {
        return UserResolver::getAdminByXid($userId);
    }
}
