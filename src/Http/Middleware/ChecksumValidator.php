<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Calculate & Validates the checksum
 */
class ChecksumValidator
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $validate = new ChecksumCalculate();
        $request = $validate->calculate($request);

        $validate = new ChecksumValidateWire();
        return $validate->handle($request, $next);
    }

}
