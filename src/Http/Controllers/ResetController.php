<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Http\JsonResponse;
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
        if (! ResetService::run($request)) {
            return;
        }

        return response()->json(['logs cleared']);
    }

    public function getKey(): ?JsonResponse
    {
        $url = ResetUrl::get();
        if (! $url) {
            return null;
        }

        return response()->json(['url' => $url]);
    }
}
