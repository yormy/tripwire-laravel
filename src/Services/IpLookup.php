<?php

namespace Yormy\TripwireLaravel\Services;

class IpLookup
{
    public function __construct(
        private readonly string $ip,
        private readonly string $service,
        private readonly string $apiKey,
    ) {
    }

    public function get(): ?stdClass
    {
        $location = new \stdClass();
        $location->continent = $location->country = $location->region = $location->city = null;

        $serviceName = $this->service;

        return $this->$serviceName($location, $this->ip, $this->apiKey);

        return $location;
    }

    protected function ipapi($location, string $ip, string $apiKey): ?stdClass
    {
        $response = $this->getResponse('http://ip-api.com/json/'.$ip.'?fields=continent,country,regionName,city');

        if (! is_object($response) || empty($response->country) || empty($response->city)) {
            return null;
        }

        $location->continent = $response->continent;
        $location->country = $response->country;
        $location->region = $response->regionName;
        $location->city = $response->city;

        return $location;
    }

    protected function extremeiplookup($location, string $ip, string $apiKey): ?stdClass
    {
        $response = $this->getResponse('https://extreme-ip-lookup.com/json/'.$ip);

        if (! is_object($response) || empty($response->country) || empty($response->city)) {
            return null;
        }

        $location->continent = $response->continent;
        $location->country = $response->country;
        $location->region = $response->region;
        $location->city = $response->city;

        return $location;
    }

    protected function ipstack($location, string $ip, string $apiKey): ?stdClass
    {
        $response = $this->getResponse('https://api.ipstack.com/'.$ip.'?access_key='.$apiKey);

        if (! is_object($response) || empty($response->country_name) || empty($response->region_name)) {
            return null;
        }

        $location->continent = $response->continent_name;
        $location->country = $response->country_name;
        $location->region = $response->region_name;
        $location->city = $response->city;

        return $location;
    }

    protected function ipdata($location, string $ip, string $apiKey): ?stdClass
    {
        $response = $this->getResponse('https://api.ipdata.co/'.$ip.'?api-key='.$apiKey);

        if (! is_object($response) || empty($response->country_name) || empty($response->region_name)) {
            return null;
        }

        $location->continent = $response->continent_name;
        $location->country = $response->country_name;
        $location->region = $response->region_name;
        $location->city = $response->city;

        return $location;
    }

    protected function ipinfo($location, string $ip, string $apiKey): ?stdClass
    {
        $response = $this->getResponse('https://ipinfo.io/'.$ip.'/geo?token='.$apiKey);

        if (! is_object($response) || empty($response->country) || empty($response->city)) {
            return null;
        }

        $location->country = $response->country;
        $location->region = $response->region;
        $location->city = $response->city;

        return $location;
    }

    public function ipregistry($location, string $ip, string $apiKey): ?stdClass
    {
        $url = 'https://api.ipregistry.co/'.$ip.'?key='.$apiKey;

        $response = $this->getResponse($url);

        if (! is_object($response) || empty($response->location)) {
            return null;
        }

        $location->continent = $response->location->continent->name;
        $location->country = $response->location->country->name;
        $location->country_code = $response->location->country->code;
        $location->region = $response->location->region->name;
        $location->city = $response->location->city;
        $location->timezone = $response->time_zone->id;
        $location->currency_code = $response->currency->code;

        $location->is_eu = $response->location->in_eu;

        if (! empty($response->location->language->code)) {
            $location->language_code = $response->location->language->code.'-'.$response->location->country->code;
        }

        return $location;
    }

    protected function getResponse($url)
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
            $response = null;
            throw new $e;
        }

        return $response;
    }
}
