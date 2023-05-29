<?php

namespace Yormy\TripwireLaravel\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

abstract class BaseException extends Exception
{
    public function render(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->renderJson($request);
        }

        return $this->renderHtml($request);
    }

    abstract protected function renderJson(Request $request);
    abstract protected function renderHtml(Request $request);
}
