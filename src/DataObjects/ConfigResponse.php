<?php

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ConfigResponse
{
    public int $code;

    public ?string $view = null;

    public ?string $redirectUrl  = null;

    public string $abort;

    public ?array $json = null;

    public ?string $exception = null;

    public ?string $messageKey = null;

    public function __construct(
        private readonly Request $request,
        array $data

    ) {
        if ($data['code'] ?? false) {
            $this->code = $data['code'];
        } else {
            $this->code = config('tripwire.block_code', 406);
        }

        if ($data['view'] ?? false) {
            $this->view = $data['view'];
        }

        if ($data['redirectUrl'] ?? false) {
            $this->redirectUrl = $data['redirectUrl'];
        }

        if ($data['abort'] ?? false) {
            $this->abort = $data['abort'];
        }

        if ($data['json'] ?? false) {
            $this->json = $data['json'];
        }

        if ($data['exception'] ?? false) {
            $this->exception = $data['exception'];
        }

        if ($data['messageKey'] ?? false) {
            $this->messageKey = $data['messageKey'];
        }
    }

    public function asContinue(): bool
    {
        if ($this->code === 200) {
            return true;
        }

        return false;
    }

    public function asException()
    {
        if ($this->exception) {
            throw new $this->exception;
        }

        return null;
    }

    public function asJson(): ?JsonResponse
    {
        if ($this->json) {
            return Response::json($this->json, $this->code);
        }

        return null;
    }

    public function asView(array $data): ?View
    {
        if ($this->view) {
            return Response::view($this->view, $data, $this->code);
        }

        return null;
    }

    public function asRedirect(): ?RedirectResponse
    {
        if ($this->redirectUrl) {
            // prevent redir to self
            if (0 === strcasecmp($this->request->url(), $this->redirectUrl)) {
                $this->asGeneralAbort();
            }

            return Redirect::to($this->redirectUrl);
        }

        return null;
    }

    public function asGeneralMessage(string $message = null): ?\Illuminate\Http\Response
    {
        if ($this->messageKey) {
            $message = __($this->messageKey);
            return Response::make($message, $this->code);
        }

        return null;
    }

    public function asGeneralAbort()
    {
        $message = 'Aborted';
        if ($this->messageKey) {
            $message = __($this->messageKey);
        }

        abort($this->code, $message);
    }
}
