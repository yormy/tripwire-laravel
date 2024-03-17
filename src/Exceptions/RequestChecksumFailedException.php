<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Exceptions;

use Illuminate\Http\Request;

class RequestChecksumFailedException extends BaseException
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
    protected function renderJson(Request $request): \Illuminate\Http\Response
    {
        //https://dev.to/jackmiras/laravels-exceptions-part-2-custom-exceptions-1367
        $status = 406;
        $error = 'Something is wrong';
        $help = 'Wrong request';

        return response(['error' => $error, 'help' => $help], $status);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function renderHtml(Request $request): string
    {
        return 'Request checksum failed renderer';
    }
}
