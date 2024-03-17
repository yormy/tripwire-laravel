<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\IpLookup;

class BaseLookup
{
    protected function getResponse(string $url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            $content = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($content);
        } catch (\ErrorException $e) {
            throw new $e();
        }

        return $response;
    }
}
