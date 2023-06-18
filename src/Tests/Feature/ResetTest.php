<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\ResetService;
use Yormy\TripwireLaravel\Tests\TestCase;

class ResetTest extends TestCase
{
    private string $tripwire ='text';
    const HTTP_TRIPWIRE_CODE = 409;

    CONST TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     * @group tripwire-reset
     */
    public function tigger_Default_disabled_Continue()
    {
        TripwireLog::truncate();
        TripwireBlock::truncate();
        $this->setDefaultConfig();
        $this->triggerBlock();

        $logCounts = TripwireLog::count();
        $this->assertGreaterThan(0, $logCounts);

        $blockCount = TripwireBlock::count();
        $this->assertGreaterThan(0, $blockCount);

        // call reset
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);
        ResetService::run($request);

        // counts must be zero
        $logCounts = TripwireLog::where('id','>',0)->count(); // refresh count
        $this->assertEquals(0, $logCounts);

        $blockCount = TripwireBlock::where('id','>',0)->count();  // refresh count
        $this->assertEquals(0, $blockCount);
    }


    // -------- HELPERS --------

    private function triggerBlock()
    {
        $this->triggerTripwire();
        $this->triggerTripwire();
        $this->triggerTripwire();
    }

    public function triggerAssertBlock()
    {
        $result = $this->triggerTripwire();
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    public function triggerAssertOke()
    {
        $result = $this->triggerTripwire();
        $this->assertEquals('next', $result);
    }


    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data= [])
    {
        config(["tripwire.trigger_response.html" => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(["tripwire.punish.score" => 21]);

        config(["tripwire.reset" => [
            'enabled' => true,
            'soft_delete' => false,
            'link_expiry_minutes' => 30,
        ]]);
    }
}
