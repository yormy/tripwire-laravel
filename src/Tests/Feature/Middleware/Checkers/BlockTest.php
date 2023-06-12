<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
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
     * @group block
     */
    public function Unblocked_Single_trigger_Block_not_added()
    {
        $this->setConfig();
        $startCount = $this->resetBlockStartCount();

        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);

        $this->assertNotBlocked($startCount);
    }

    /**
     * @test
     * @group block
     */
    public function Unblocked_Many_triggers_Block_added()
    {
        $this->setConfig();
        $startCount = $this->resetBlockStartCount();

        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);

        $this->assertBlockAddedToDatabase($startCount);
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
