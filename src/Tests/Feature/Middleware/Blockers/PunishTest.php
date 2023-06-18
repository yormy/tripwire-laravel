<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Carbon\Carbon;
use Yormy\TripwireLaravel\Http\Middleware\Blockers\TripwireBlockHandlerAll;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\BlockTestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class PunishTest extends TestCase
{
    use TripwireTestTrait;
    use BlockTestTrait;

    const TRIPWIRE_TRIGGER = 'BLOCK_TEST';

    const BLOCK_CODE = 401;

    protected string $tripwire ='text';

    protected $tripwireClass = Text::class;

    /**
     * @test
     * @group tripwire-punish
     */
    public function Block_Added_Exponential_delay()
    {
        TripwireBlock::truncate();
        TripwireLog::truncate();
        $this->setConfig();
        $startCount = $this->resetBlockStartCount();

        $this->triggerBlock();
        $this->assertBlockAddedToDatabase($startCount);

        $penaltyBase = config('tripwire.punish.penalty_seconds');
        $this->assertDiffInSeconds($penaltyBase); // penalty seconds

        $this->triggerBlock();
        $penalty = $penaltyBase + pow($penaltyBase, 1);
        $this->assertDiffInSeconds($penalty); // penalty + penalty power of 1

        $this->triggerBlock();
        $penalty = $penaltyBase + pow($penaltyBase, 2);
        $this->assertDiffInSeconds($penalty); // penalty + penalty power of 2

        $this->triggerBlock();
        $penalty = $penaltyBase + pow($penaltyBase, 3);
        $this->assertDiffInSeconds($penalty); // penalty + penalty power of 3
    }

    private function assertDiffInSeconds(int $expectedDiff)
    {
        $latest = TripwireBlock::where('id', '>', 0)->orderBy('id', 'desc')->first();
        $createdAt = $latest->created_at;
        $diffInSeconds = $createdAt->diffInSeconds($latest->blocked_until);
        $this->assertEquals($expectedDiff, $diffInSeconds);
    }

    // ---------- HELPERS ---------
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
        config(["tripwire.punish.within_minutes" => 10000]);
        config(["tripwire.punish.penalty_seconds" => 10]);

        $this->setBlockConfig();
    }
}
