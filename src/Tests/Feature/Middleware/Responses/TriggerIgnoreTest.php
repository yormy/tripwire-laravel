<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class TriggerIgnoreTest extends TestCase
{
    private string $tripwire ='text';
    const HTTP_TRIPWIRE_CODE = 409;

    CONST TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     * @group tripwire-ignore
     */
    public function Package_disabled_Trigger_No_exception()
    {
        $this->setDefaultConfig(["exception" => TripwireFailedException::class]);
        config(["tripwire.enabled" => false]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     * @group tripwire-ignore
     */
    public function Feature_disabled_Trigger_No_exception()
    {
        $this->setDefaultConfig(["exception" => TripwireFailedException::class]);
        config(["tripwire_wires.text.enabled" => false]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }



    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data)
    {
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire.trigger_response.html" => $data]);
    }
}
