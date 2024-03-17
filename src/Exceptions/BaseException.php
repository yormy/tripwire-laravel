<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BaseException extends Exception
{
    public function render(Request $request): Response | string
    {
        $this->dispatchEvents($request);

        if ($request->wantsJson()) {
            return $this->renderJson($request);
        }

        return $this->renderHtml($request);
    }

    abstract protected function dispatchEvents(Request $request): void;

    abstract protected function renderJson(Request $request): Response;

    abstract protected function renderHtml(Request $request): string;
}
