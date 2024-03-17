<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yormy\TripwireLaravel\Services\ResetService;
use Yormy\TripwireLaravel\Services\ResetUrl;

/**
 * @group Tripwire
 *
 * @subgroup Reset
 */
class ResetController extends controller
{
    /**
     * Reset
     *
     * Reset the blocks so the ip/user has access again
     *
     * @response {
     *   "logs cleared
     *  }
     */
    public function reset(Request $request): JsonResponse
    {
        if (! ResetService::run($request)) {
            return response()->json([], 404);
        }

        return response()->json(['logs cleared']);
    }

    /**
     * Get reset url
     *
     * Returns the url to reset the blocks
     *
     * @response {
     *  "url": 'https://localhost.com/reset/325235235235253252',
     * }
     */
    public function getKey(): ?JsonResponse
    {
        $url = ResetUrl::get();
        if (! $url) {
            return response()->json([], 404);
        }

        return response()->json(['url' => $url]);
    }
}
