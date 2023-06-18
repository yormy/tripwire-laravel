<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;
use Yormy\TripwireLaravel\Services\ResetService;
use Yormy\TripwireLaravel\Services\ResetUrl;

class ResetController extends controller
{
    public function reset(Request $request)
    {
        if (!config('tripwire.reset.enabled')) {
            return;
        }

        ResetService::run($request);

        return response()->json(['logs cleared']);
    }

    public function getKey()
    {
        $url = ResetUrl::get();

        return response()->json(['url' => $url]);
    }
}
