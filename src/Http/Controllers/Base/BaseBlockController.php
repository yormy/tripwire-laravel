<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Controllers\Base;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

abstract class BaseBlockController extends controller
{
    /**
     * Block Index
     *
     * Get all blocks for this user
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiDTOCollection Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function index(string $user_xid): Response
    {
        $member = $this->getUser($user_xid);

        $logRepository = new BlockRepository();
        $blocks = $logRepository->getAllForUser($member);

        $dto = BlockDataResponse::collect($blocks);

        return ApiResponse::withData($dto)
            ->successResponse();
    }

    abstract public function getUser(string|int $userId): mixed;
}
