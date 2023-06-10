<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Yormy\TripwireLaravel\Http\Controllers\Resources\LogCollection;
use Yormy\TripwireLaravel\Repositories\LogRepository;

class LogController extends controller
{
    public function index()
    {
        $logRepository = new LogRepository();
        $logs = $logRepository->getAll();

        $logs = (new LogCollection($logs))->toArray(null);

        return response()->json($logs);
    }
}
