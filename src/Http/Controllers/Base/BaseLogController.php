<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Controllers\Base;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\DataObjects\Log\LogDataResponse;
use Yormy\TripwireLaravel\Repositories\LogRepository;

abstract class BaseLogController extends controller
{
    /**
     * Log Index
     *
     * Get all logs for this user
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Log\LogDataResponse
     *
     * @responseApiDTOCollection Yormy\TripwireLaravel\DataObjects\Log\LogDataResponse
     *
     * @responseApiType successResponse
     */
    public function index(string $user_xid): Response
    {
        $user = $this->getUser($user_xid);

        $logRepository = new LogRepository();
        $logs = $logRepository->getAllForUser($user);

        $dto = LogDataResponse::collect($logs);

        return ApiResponse::withData($dto)
            ->successResponse();
    }

    abstract public function getUser(string | int $userId): mixed;
}
