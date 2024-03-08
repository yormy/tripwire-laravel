<?php

namespace Yormy\TripwireLaravel\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockCollection;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockResource;
use Yormy\TripwireLaravel\Http\Controllers\System\Requests\BlockAddRequest;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

class SystemBlockController extends Controller
{
    public function index(Request $request): Response
    {
        $blockRepository = new BlockRepository();
        $blocks = $blockRepository->getAll();

        $blocks = (new BlockCollection($blocks))->toArray($request);
        $blocks = $this->decorateAllWithStatus($blocks);

        return ApiResponse::withData($blocks)
            ->successResponse();
    }

    public function store(BlockAddRequest $request)
    {
        $blockRepository = new BlockRepository();
        $block = $blockRepository->addManualBlock($request->validated());

        $block = (new BlockResource($block))->toArray($request);
        $block = $this->decorateWithStatus($block);

        return ApiResponse::withData($block)
            ->successResponseCreated();
    }

    private function decorateAllWithStatus($values): array
    {
        foreach ($values as $index => $data) {
            $values[$index] = $this->decorateWithStatus($data);
        }

        return $values;
    }

    private function decorateWithStatus($data): array
    {
        $status = '';
        if ($data['blocked_until'] >= Carbon::now()) {
            $status = [
                'key' => 'active',
                'nature' => 'danger',
                'text' => __('tripwire::logitem.block_active')
            ];
        }

        $data['status'] = $status;

        return $data;
    }
}
