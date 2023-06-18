<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;

class ResponseDeterminer
{
    public int $code;

    public function __construct(
        private readonly JsonResponseConfig | HtmlResponseConfig $config,
        private string $currentUrl = ''
    )
    {
        if (!isset($this->config->code) || $this->config->code === 0 ) {
            $this->code = 401;
        } else {
            $this->code = $this->config->code;
        }
    }

    public function respondWithJson(array $data = [])
    {
        $this->asException();

        if ($response = $this->asJson()) {
            return $response;
        }

        if ($response = $this->asGeneralMessage()) {
            return $response;
        }

        return $this->asGeneralAbort();
    }

    public function respondWithHtml(array $data = [])
    {
        $this->asException();

        if ($response = $this->asView($data)) {
            return $response;
        }

        if ($response = $this->asRedirect($data)) {
            return $response;
        }

        if ($response = $this->asGeneralMessage()) {
            return $response;
        }

        return $this->asGeneralAbort();
    }

    public function asContinue(): bool
    {
        if (isset($this->config->code) && $this->config->code === 200) {
            return true;
        }

        return false;
    }

    public function asException()
    {
        if (isset($this->config->exception)) {
            throw new $this->config->exception;
        }

        return null;
    }

    public function asJson(): ?JsonResponse
    {
        if (isset($this->config->json)) {
            return Response::json($this->config->json, $this->code);
        }

        return null;
    }

    public function asView(array $data): ?View
    {
        if (isset($this->config->view)) {
            return Response::view($this->config->view, $data, $this->code);
        }

        return null;
    }

    public function asRedirect(): ?RedirectResponse
    {
        if (isset($this->config->redirectUrl)) {
            // prevent redir to self
            if (0 === strcasecmp($this->currentUrl, $this->config->redirectUrl)) {
                $this->asGeneralAbort();
            }

            return Redirect::to($this->config->redirectUrl);
        }

        return null;
    }

    public function asGeneralMessage(string $message = null): ?\Illuminate\Http\Response
    {
        if (isset($this->config->messageKey)) {
            $message = __($this->config->messageKey);
            return Response::make($message, $this->code);
        }

        return null;
    }

    public function asGeneralAbort()
    {
        $message = 'Aborted';
        if (isset($this->config->messageKey)) {
            $message = __($this->config->messageKey);
        }

        return Response::make($message, $this->code);
    }
}
