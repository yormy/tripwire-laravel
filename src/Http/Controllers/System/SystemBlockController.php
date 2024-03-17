<?php

namespace Yormy\TripwireLaravel\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\DataObjects\Block\BlockDataRequest;
use Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

/**
 * @group Tripwire
 *
 * @subgroup System
 */
class SystemBlockController extends Controller
{
    /**
     * Block Index
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiDTOCollection Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function index(): Response
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
     *
     * @responseApiDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
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

    /**
     * Show
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function show(Request $request, TripwireBlock $block_xid)
    {
        return $this->returnBlock($request, $block_xid);
    }

    /**
     * Persist
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function persist(Request $request, TripwireBlock $block_xid)
    {
        $block_xid->persistent_block = true;
        $block_xid->save();

        return $this->returnBlock($request, $block_xid);
    }

    /**
     * Un-Persist
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function unpersist(Request $request, TripwireBlock $block_xid)
    {
        $block_xid->persistent_block = false;
        $block_xid->save();

        return $this->returnBlock($request, $block_xid);
    }

    /**
     * Un-block
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function unblock(Request $request, TripwireBlock $block_xid)
    {
        $block_xid->blocked_until = null;
        $block_xid->save();

        return $this->returnBlock($request, $block_xid);
    }

    /**
     * Delete
     *
     * @responseFieldsDTO Yormy\TripwireLaravel\DataObjects\Block\BlockDataResponse
     *
     * @responseApiType successResponse
     */
    public function delete(Request $request, TripwireBlock $block_xid)
    {
        $block_xid->delete();

        return $this->returnBlock($request, $block_xid);
    }

    private function returnBlock(Request $request, TripwireBlock $tripwireBlock)
    {
        $dto = BlockDataResponse::fromModel($tripwireBlock);

        return ApiResponse::withData($dto)
            ->successResponse();
    }
}
