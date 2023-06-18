<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Http\Middleware\HoneypotsCheck;
use Yormy\TripwireLaravel\Tests\TestCase;

class HoneypotTest extends TestCase
{
    private string $tripwire ='text';
    const HTTP_TRIPWIRE_CODE = 409;

    CONST TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     * @group tripwire-honeypot
     */
    public function Trigger_honeypost_Block()
    {
        $this->setDefaultConfig();
        $this->triggerHoneypotOke();

        config(["tripwire.honeypots" => [
            'attack_score' => 10,
            'must_be_missing_or_false' => ['foo']
        ]]);
        $this->triggerHoneypotBlock();
    }


    // -------- HELPERS --------
    public function triggerHoneypotBlock()
    {
        $result = $this->testHoneypot();
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    public function triggerHoneypotOke()
    {
        $result = $this->testHoneypot();
        $this->assertEquals('next', $result);
    }


    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', 'non blocked test');

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function testHoneypot()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', 'non blocked test');

        return (new HoneypotsCheck($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data= [])
    {
        config(["tripwire.trigger_response.html" => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(["tripwire.punish.score" => 21]);
    }
}
