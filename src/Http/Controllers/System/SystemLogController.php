<?php

namespace Yormy\TripwireLaravel\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\DataObjects\Log\LogDataResponse;
use Yormy\TripwireLaravel\Repositories\LogRepository;

/**
 * @group Tripwire
 *
 * @subgroup System Logs
 */
class SystemLogController extends Controller
{
    /**
     * Index
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Log\LogDataResponse
     * @responseApiDTOCollection Yormy\TripwireLaravel\DataObjects\Log\LogDataResponse
     * @responseApiType successResponse
     */
    public function index(Request $request): Response
    {
        $logRepository = new LogRepository();
        $logs = $logRepository->getAll();

        $dto = LogDataResponse::collect($logs);

        return ApiResponse::withData($dto)
            ->successResponse();
    }
}
