<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yormy\TripwireLaravel\Services\ResetService;
use Yormy\TripwireLaravel\Services\ResetUrl;

class ResetController extends controller
{
    /**
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function reset(Request $request)
    {
        if (! config('tripwire.reset.enabled')) {
            return;
        }

        ResetService::run($request);

        return response()->json(['logs cleared']);
    }

    public function getKey(): \Illuminate\Http\JsonResponse
    {
        $url = ResetUrl::get();

        return response()->json(['url' => $url]);
    }
}
