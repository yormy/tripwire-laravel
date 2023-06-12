<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\TriggerIgnores;

use Illuminate\Http\Request;
use Mockery;
use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Tests\TestCase;

class BaseTriggerIgnore extends TestCase
{
    private string $tripwire ='text';
    const HTTP_TRIPWIRE_CODE = 409;

    CONST TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    protected function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        $request = $this->createRequest('post','', 'path/to/location');
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    protected function createRequest(
        $method,
        $content,
        $uri = '/test',
        $server = ['CONTENT_TYPE' => 'application/json'],
        $parameters = [],
        $cookies = [],
        $files = []
    ) {
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

    protected function setDefaultConfig()
    {
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire.trigger_response.html" => ["exception" => TripwireFailedException::class]]);
    }
}
