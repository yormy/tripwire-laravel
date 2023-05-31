<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\JsonResponse;
use Exception;

class ConfigResponse
{
    public int $code;

    public ?string $view = null;

    public ?string $redirectUrl  = null;

    public string $abort;

    public ?array $json = null;

    public ?string $exception = null;

    public ?string $message = null;

    public function __construct(
        private readonly Request $request,
        private readonly ?string $checker = null
    ) {

        $generalResponse = config('tripwire.response.block');
        $blockResponse = $generalResponse;
        if ($checker) {
            $checkerResponse = config('tripwire.middleware.' . $checker. '.response.block', false);
            if (is_array($checkerResponse)) {
                $blockResponse = $checkerResponse;
            }
        }

        if ($blockResponse['code'] ?? false) {
            $this->code = $blockResponse['code'];
        } else {
            $this->code = config('tripwire.block_code', 406);
        }

        if ($blockResponse['view'] ?? false) {
            $this->view = $blockResponse['view'];
        }

        if ($blockResponse['redirectUrl'] ?? false) {
            $this->redirectUrl = $blockResponse['redirectUrl'];
        }

        if ($blockResponse['abort'] ?? false) {
            $this->abort = $blockResponse['abort'];
        }

        if ($blockResponse['json'] ?? false) {
            $this->json = $blockResponse['json'];
        }

        if ($blockResponse['exception'] ?? false) {
            $this->exception = $blockResponse['exception'];
        }

        if ($blockResponse['messageKey'] ?? false) {
            $this->messageKey = $blockResponse['messageKey'];
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

    public function asView(array $data): ?Response
    {
        if ($this->view) {
            return Response::view($this->view, $data, $this->code);
        }

        return null;
    }

    public function asRedirect(): ?Redirect
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
