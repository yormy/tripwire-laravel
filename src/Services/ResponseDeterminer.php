<?php

namespace Yormy\TripwireLaravel\Services;


use Yormy\TripwireLaravel\DataObjects\ConfigResponse;

class ResponseDeterminer
{

    public function __construct(private readonly ConfigResponse $configResponse)
    {}

    public function respondWithJson(array $data = [])
    {
        $this->configResponse->asException();

        if ($response = $this->configResponse->asJson()) {
            return $response;
        }

        if ($response = $this->configResponse->asGeneralMessage()) {
            return $response;
        }

        $this->configResponse->asGeneralAbort();
    }

    public function respondWithHtml(array $data = [])
    {
        $this->configResponse->asException();

        if ($response = $this->configResponse->asView($data)) {
            return $response;
        }

        if ($response = $this->configResponse->asRedirect($data)) {
            return $response;
        }

        if ($response = $this->configResponse->asGeneralMessage()) {
            return $response;
        }

        $this->configResponse->asGeneralAbort();
    }
}
