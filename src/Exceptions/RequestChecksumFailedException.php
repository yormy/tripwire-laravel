<?php

namespace Yormy\TripwireLaravel\Exceptions;

use Illuminate\Http\Request;

class RequestChecksumFailedException extends BaseException
{
    protected function dispatchEvents(Request $request)
    {

    }

    protected function renderJson(Request $request)
    {
        //https://dev.to/jackmiras/laravels-exceptions-part-2-custom-exceptions-1367
        $status = 400;
        $error = 'Something is wrong';
        $help = 'Contact the sales team to verify';

        return response(['error' => $error, 'help' => $help], $status);
    }

    protected function renderHtml(Request $request)
    {
        return 'dddd';
    }
}
