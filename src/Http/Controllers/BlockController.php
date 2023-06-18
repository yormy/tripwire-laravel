<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Routing\Controller;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockCollection;
use Yormy\TripwireLaravel\Http\Controllers\Resources\LogCollection;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class BlockController extends controller
{
    public function index()
    {
        $blockRepository = new BlockRepository();
        $blocks = $blockRepository->getAll();

        $blocks = (new BlockCollection($blocks))->toArray(null);

        return response()->json($blocks);
    }

    public function show($blockId)
    {
        if (! is_numeric($blockId)) {
            return response()->json([]);
        }

        $logRepository = new LogRepository();
        $logs = $logRepository->getByBlockId($blockId);

        $logs = (new LogCollection($logs))->toArray(null);

        return response()->json($logs);
    }
}
