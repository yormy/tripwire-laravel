<?php

namespace Yormy\TripwireLaravel\Exceptions;

use Illuminate\Http\Request;

class TripwireFailedException extends BaseException
{
    /**
     * @return void
     */
    protected function dispatchEvents(Request $request)
    {
    }

    /**
     * @return \Illuminate\Http\Response
     */
    protected function renderJson(Request $request)
    {
        $status = config('tripwire.block_code');
        $error = __('tripwire::exceptions.tripwire_failed_exception.error');
        $message = __('tripwire::exceptions.tripwire_failed_exception.message');

        return response(['error' => $error, 'message' => $message], $status);
    }

    /**
     * @return string
     */
    protected function renderHtml(Request $request)
    {
        return 'Tripwire Failed Exception Renderer';
    }
}
