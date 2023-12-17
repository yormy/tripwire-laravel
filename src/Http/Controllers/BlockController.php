<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockCollection;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockResource;
use Yormy\TripwireLaravel\Http\Controllers\Resources\LogCollection;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;
use Yormy\Apiresponse\Facades\ApiResponse;

class BlockController extends controller
{
    public function index(Request $request, $member_xid): Response
    {
        $blockRepository = new BlockRepository();
        $blocks = $blockRepository->getAll();

        $blocks = (new BlockCollection($blocks))->toArray($request);

        $blocks = $this->decorateWithStatus($blocks);

        return ApiResponse::withData($blocks)
            ->successResponse();
    }

    public function show(Request $request, $block_xid)
    {
        $blockRepository = new BlockRepository();
        $tripwireBlock = $blockRepository->findByXid($block_xid);

        $block = (new BlockResource($tripwireBlock))->toArray($request);

        return ApiResponse::withData($block)
            ->successResponse();
    }

//    public function show11($blockId): Response
//    {
//        if (! is_numeric($blockId)) {
//            return response()->json([]);
//        }
//
//        $logRepository = new LogRepository();
//        $logs = $logRepository->getByBlockId($blockId);
//
//        $logs = (new LogCollection($logs))->toArray(null);
//
//        return response()->json($logs);
//    }

    private function decorateWithStatus($values): array
    {
        foreach ($values as $index => $data) {
            $status = '';
            if ($data['blocked_until'] >= Carbon::now()) {
                $status = [
                    'key' => 'active',
                    'nature' => 'danger',
                    'text' => __('tripwire::logitem.block_active')
                ];
            }

            $values[$index]['status'] = $status;
        }

        return $values;
    }
}
