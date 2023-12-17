<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Members;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\Http\Controllers\Base\BaseLogController;
use Yormy\TripwireLaravel\Http\Controllers\Resources\LogCollection;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Repositories\LogRepository;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

class MemberLogController extends BaseLogController
{
    public function getUser($userId)
    {
        return UserResolver::getMemberById($userId);
    }
}
