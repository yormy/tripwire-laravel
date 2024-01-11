<?php

namespace Yormy\TripwireLaravel\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockCollection;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

class SystemBlockController extends Controller
{
    public function index(Request $request): Response
    {
        $logRepository = new BlockRepository();
        $blocks = $logRepository->getAll();

        $blocks = (new BlockCollection($blocks))->toArray($request);
        $blocks = $this->decorateWithStatus($blocks);

        return ApiResponse::withData($blocks)
            ->successResponse();
    }

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
