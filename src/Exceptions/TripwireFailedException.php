<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TripwireFailedException extends BaseException
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function dispatchEvents(Request $request): void
    {
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function renderJson(Request $request): Response
    {
        $status = config('tripwire.block_code');
        $error = __('tripwire::exceptions.tripwire_failed_exception.error');
        $message = __('tripwire::exceptions.tripwire_failed_exception.message');

        return response(['error' => $error, 'message' => $message], $status);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function renderHtml(Request $request): string
    {
        return 'Tripwire Failed Exception Renderer';
    }
}
