<?php

namespace Yormy\TripwireLaravel\Http\Controllers\Base;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\TripwireLaravel\Http\Controllers\Resources\LogCollection;
use Yormy\TripwireLaravel\Repositories\LogRepository;

abstract class BaseLogController extends controller
{
    public function index(Request $request, $userId): Response
    {
        $user = $this->getUser($userId);

        $logRepository = new LogRepository();
        $logs = $logRepository->getAllForUser($user);

        $logs = (new LogCollection($logs))->toArray($request);
        $logs = $this->decorateWithStatus($logs);

        return ApiResponse::withData($logs)
            ->successResponse();
    }

    private function decorateWithStatus($values): array
    {
        $scoreMediumThreshold = 20;
        $scoreHighThreshold = 50;

        foreach ($values as $index => $data) {
            $status = '';
            if ($data['event_score'] >= $scoreMediumThreshold) {
                $status = [
                    'key' => 'medium',
                    'nature' => 'warning',
                    'text' => __('tripwire::logitem.score.medium')
                ];
            }
            if ($data['event_score'] >= $scoreHighThreshold) {
                $status = [
                    'key' => 'high',
                    'nature' => 'danger',
                    'text' => __('tripwire::logitem.score.high')
                ];
            }


            $values[$index]['status'] = $status;
        }

        return $values;
    }
}
