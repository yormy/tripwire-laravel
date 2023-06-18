<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Blockers\TripwireBlockHandlerAll;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\BlockTestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BlockTest extends TestCase
{
    use TripwireTestTrait;
    use BlockTestTrait;

    const TRIPWIRE_TRIGGER = 'BLOCK_TEST';

    const BLOCK_CODE = 401;

    protected string $tripwire ='text';

    protected $tripwireClass = Text::class;

    /**
     * @test
     * @group tripwire-block
     */
    public function Unblocked_Single_trigger_Block_not_added()
    {
        TripwireBlock::truncate();
        $this->setConfig();
        $startCount = $this->resetBlockStartCount();

        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);

        $this->assertNotBlocked($startCount);
    }

    /**
     * @test
     * @group tripwire-block
     */
    public function Unblocked_Many_triggers_Block_added()
    {
        TripwireBlock::truncate();
        $this->setConfig();
        $startCount = $this->resetBlockStartCount();

        $this->triggerBlock();

        $this->assertBlockAddedToDatabase($startCount);
    }

    /**
     * @test
     * @group tripwire-block
     */
    public function Unblocked_Normal_request_Ok()
    {
        TripwireBlock::truncate();
        $this->setConfig();

        $result = $this->testBlockHandlerAll();
        $this->assertEquals('next', $result);
    }

    /**
     * @test
     * @group tripwire-block
     */
    public function Blocked_Normal_request_Blocked()
    {
        TripwireBlock::truncate();
        $this->setConfig();
        $this->triggerBlock();

        $result = $this->testBlockHandlerAll();
        $this->assertNotEquals('next', $result);
    }

    /**
     * @test
     * @group tripwire-block
     */
    public function Blocked_training_Normal_request_Ok()
    {
        TripwireBlock::truncate();
        $this->setConfig();

        config(["tripwire.training_mode" => true]);
        $this->triggerBlock();
        $result = $this->testBlockHandlerAll();
        $this->assertContinue($result);

        config(["tripwire.training_mode" => false]);
        $this->triggerBlock();
        $result = $this->testBlockHandlerAll();
        $this->assertBlocked($result);
    }

    private function assertContinue($result)
    {
        $this->assertEquals('next', $result);
    }

    private function assertBlocked($result)
    {
        $this->assertNotEquals('next', $result);
    }

    protected function testBlockHandlerAll()
    {
        $request = $this->app->request;
        $request->query->set('foo', 'normal input');

        $checker = new TripwireBlockHandlerAll();

        return $checker->handle($request, $this->getNextClosure());
    }

    private function triggerBlock()
    {
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
    }


    protected function setConfig()
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);

        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(["tripwire.punish.score" => 21]);

        $this->setBlockConfig();
    }
}