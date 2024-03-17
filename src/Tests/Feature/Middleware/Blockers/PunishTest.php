<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Blockers;

use Yormy\TripwireLaravel\Http\Middleware\Blockers\TripwireBlockHandlerAll;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\BlockTestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class PunishTest extends TestCase
{
    use BlockTestTrait;
    use TripwireTestTrait;

    const TRIPWIRE_TRIGGER = 'BLOCK_TEST';

    const BLOCK_CODE = 401;

    protected string $tripwire = 'text';

    protected string $tripwireClass = Text::class;

    /**
     * @test
     *
     * @group tripwire-punish
     */
    public function Block_Added_Exponential_delay(): void
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

    private function assertDiffInSeconds(int $expectedDiff): void
    {
        $latest = TripwireBlock::where('id', '>', 0)->orderBy('id', 'desc')->first();
        $createdAt = $latest->created_at;
        $diffInSeconds = $createdAt->diffInSeconds($latest->blocked_until);
        $this->assertEquals($expectedDiff, $diffInSeconds);
    }

    // ---------- HELPERS ---------
    private function assertContinue($result): void
    {
        $this->assertEquals('next', $result);
    }

    private function assertBlocked($result): void
    {
        $this->assertNotEquals('next', $result);
    }

    protected function testBlockHandlerAll()
    {
        $request = $this->app->request;
        $request->query->set('foo', 'normal input');

        $wite = new TripwireBlockHandlerAll();

        return $wite->handle($request, $this->getNextClosure());
    }

    private function triggerBlock(): void
    {
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.reject_response.html" => $settings]);
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);

        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(['tripwire.punish.score' => 21]);
        config(['tripwire.punish.within_minutes' => 10000]);
        config(['tripwire.punish.penalty_seconds' => 10]);

        $this->setBlockConfig();
    }
}
