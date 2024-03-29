<?php

namespace Yormy\TripwireLaravel\Tests\Traits;

trait RequestTrait
{
    /**
     * @param  array<string>  $server
     * @param  array<string>  $parameters
     * @param  array<string>  $cookies
     * @param  array<string>  $files
     */
    public function createRequest(
        string $method,
        string $content,
        string $uri = '/test',
        array $server = ['CONTENT_TYPE' => 'application/json'],
        array $parameters = [],
        array $cookies = [],
        array $files = []
    ): \Illuminate\Http\Request {
        $request = new \Illuminate\Http\Request;

        return $request->createFromBase(
            \Symfony\Component\HttpFoundation\Request::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }
}
