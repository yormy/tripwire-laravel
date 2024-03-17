<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Honeypot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class HoneypotTest extends TestCase
{
    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     *
     * @group tripwire-honeypot
     */
    public function Trigger_honeypot_Log(): void
    {
        $startCount = Tripwirelog::count();

        $this->setDefaultConfig();
        $this->triggerHoneypotOke();

        $this->setTrippableConfig();

        $this->triggerHoneypotBlock();

        $this->assertLog($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-honeypot
     */
    public function Trigger_honeypot_empty_Oke(): void
    {
        $this->setDefaultConfig();
        $this->setTrippableConfig();

        $result = $this->triggerHoneypotEmpty();

        $this->assertOke($result);
    }

    /**
     * @test
     *
     * @group tripwire-honeypot
     */
    public function Trigger_honeypot_false_Oke(): void
    {
        $this->setDefaultConfig();
        $this->setTrippableConfig();

        $result = $this->triggerHoneypotFalse();

        $this->assertOke($result);
    }

    /**
     * @test
     *
     * @group tripwire-honeypot
     */
    public function Trigger_honeypot_zero_Oke(): void
    {
        $this->setDefaultConfig();
        $this->setTrippableConfig();

        $result = $this->triggerHoneypotZero();

        $this->assertOke($result);
    }

    private function assertOke($result)
    {
        $this->assertEquals($result, 'next');
    }

    private function assertNotLogged(int $startCount)
    {
        $endCount = Tripwirelog::count();
        $this->assertEquals($startCount, $endCount);
    }

    private function assertLog(int $startCount)
    {
        $endCount = Tripwirelog::count();
        $this->assertGreaterThan($startCount, $endCount);
    }

    /**
     * @test
     *
     * @group tripwire-honeypot
     */
    public function Trigger_multi_honeypost_Block(): void
    {
        $blockCountStart = TripwireBlock::count();

        $this->setDefaultConfig();
        $this->triggerHoneypotOke();

        $this->setTrippableConfig();

        $this->triggerHoneypotBlock();
        $this->triggerHoneypotBlock();
        $this->triggerHoneypotBlock();

        $blockCountEnd = TripwireBlock::count();
        $this->assertGReaterThan($blockCountStart, $blockCountEnd);
    }

    // -------- HELPERS --------
    public function triggerHoneypotBlock(): void
    {
        $result = $this->triggerHoneypot();

        $this->assertNotEquals($result, 'next');
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    public function triggerHoneypotOke(): void
    {
        $result = $this->triggerHoneypot();
        $this->assertEquals('next', $result);
    }

    private function triggerTripwire()
    {
        $request = request(); // default is as HTML
        $request->query->set('foo', 'non blocked test');

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function triggerHoneypot()
    {
        $request = request(); // default is as HTML
        $request->query->set('foo', 'non blocked test');

        return (new Honeypot($request))->handle($request, $this->getNextClosure());
    }

    private function triggerHoneypotEmpty()
    {
        $request = request();
        $request->query->set('foo', '');

        return (new Honeypot($request))->handle($request, $this->getNextClosure());
    }

    private function triggerHoneypotZero()
    {
        $request = request();
        $request->query->set('foo', 0);

        return (new Honeypot($request))->handle($request, $this->getNextClosure());
    }

    private function triggerHoneypotFalse()
    {
        $request = request();
        $request->query->set('foo', false);

        return (new Honeypot($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire_wires.honeypot.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);
    }

    private function setTrippableConfig()
    {
        config([
            'tripwire_wires.honeypot.attack_score' => 10,
            'tripwire_wires.honeypot.tripwires' => [
                'foo',
            ],
        ]);
    }
}
