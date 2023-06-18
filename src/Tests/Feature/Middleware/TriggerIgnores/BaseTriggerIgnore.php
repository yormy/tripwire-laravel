<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\TriggerIgnores;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\RequestTrait;

class BaseTriggerIgnore extends TestCase
{
    use RequestTrait;

    private string $tripwire = 'text';

    const HTTP_TRIPWIRE_CODE = 409;

    const TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    protected function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        $request = $this->createRequest('post', '', 'path/to/location');
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    protected function setDefaultConfig(): void
    {
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(['tripwire.trigger_response.html' => ['exception' => TripwireFailedException::class]]);
    }
}
