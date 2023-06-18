<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Http\Middleware\HoneypotsWire;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class HoneypotTest extends TestCase
{
    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     * @group tripwire-honeypot
     */
    public function Trigger_honeypot_log()
    {
        $startCount = Tripwirelog::count();

        $this->setDefaultConfig();
        $this->triggerHoneypotOke();
        config([
            "tripwire_wires.honeypots.attack_score" => 10,
            "tripwire_wires.honeypots.tripwires" => [
                'foo'
            ]
        ]);

        $this->triggerHoneypotBlock();

        $endCount = Tripwirelog::count();

        $this->assertGreaterThan($startCount, $endCount);
    }

    /**
     * @test
     * @group tripwire-honeypot
     */
    public function Trigger_multi_honeypost_Block()
    {
        $blockCountStart = TripwireBlock::count();

        $this->setDefaultConfig();
        $this->triggerHoneypotOke();

        config([
            "tripwire_wires.honeypots.attack_score" => 10,
            "tripwire_wires.honeypots.tripwires" => [
                'foo'
            ]
        ]);

        $this->triggerHoneypotBlock();
        $this->triggerHoneypotBlock();
        $this->triggerHoneypotBlock();

        $blockCountEnd = TripwireBlock::count();
        $this->assertGReaterThan($blockCountStart, $blockCountEnd);
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

        return (new HoneypotsWire($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data= [])
    {
        config(["tripwire.trigger_response.html" => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire.punish.score" => 21]);
    }
}
