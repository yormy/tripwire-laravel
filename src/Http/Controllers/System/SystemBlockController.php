<?php

namespace Yormy\TripwireLaravel\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\DataObjects\Block\BlockDataRequest;
use Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

/**
 * @group Tripwire
 *
 * @subgroup System Blocks
 */
class SystemBlockController extends Controller
{
    /**
     * Index
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     * @responseApiDTOCollection Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     * @responseApiType successResponse
     */
    public function index(Request $request): Response
    {
        $blockRepository = new BlockRepository();
        $blocks = $blockRepository->getAll();

        $dto = BlockDataResponse::collect($blocks);

        return ApiResponse::withData($dto)
            ->successResponse();
    }

    /**
     * Store
     *
     * @bodyParamDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataRequest
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     * @responseApiDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     * @responseApiType successResponseCreated
     */
    public function store(BlockDataRequest $data)
    {
        $blockRepository = new BlockRepository();
        $block = $blockRepository->addManualBlock($data);

        $dto = BlockDataResponse::fromModel($block);

        return ApiResponse::withData($dto)
            ->successResponseCreated();
    }
}
