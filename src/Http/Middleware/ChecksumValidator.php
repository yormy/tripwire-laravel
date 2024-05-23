<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Calculate & Validates the checksum
 */
class ChecksumValidator
{
    public function handle(Request $request, Closure $next): mixed
    {
        $validate = new ChecksumCalculate();

        $request = $validate->calculate($request);

        $validate = new ChecksumValidateWire();

        return $validate->handle($request, $next);
    }
}
