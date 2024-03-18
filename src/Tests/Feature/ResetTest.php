<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Yormy\TripwireLaravel\Http\Controllers\ResetController;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\ResetService;
use Yormy\TripwireLaravel\Tests\TestCase;

class ResetTest extends TestCase
{
    private string $tripwire = 'text';

    const HTTP_TRIPWIRE_CODE = 409;

    const TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     *
     * @group tripwire-reset
     */
    public function Reset_disabled_Activate_Nothing(): void
    {
        config(['tripwire.reset' => [
            'enabled' => false,
            'soft_delete' => false,
            'link_expiry_minutes' => 30,
        ]]);

        $resetController = new ResetController();
        $request = request();
        $result = $resetController->reset($request);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $result->getStatusCode());
    }

    /**
     * @test
     *
     * @group tripwire-reset
     */
    public function Reset_enabled_Activate_Oke(): void
    {
        config(['tripwire.reset' => [
            'enabled' => true,
            'soft_delete' => false,
            'link_expiry_minutes' => 30,
        ]]);

        $resetController = new ResetController();

        $request = request();
        $result = $resetController->reset($request);
        $this->assertNotEquals(null, $result);
    }

    /**
     * @test
     *
     * @group tripwire-reset
     */
    public function tigger_Default_disabled_Continue(): void
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
        $request = request(); // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);
        ResetService::run($request);

        // counts must be zero
        $logCounts = TripwireLog::where('id', '>', 0)->count(); // refresh count
        $this->assertEquals(0, $logCounts);

        $blockCount = TripwireBlock::where('id', '>', 0)->count();  // refresh count
        $this->assertEquals(0, $blockCount);
    }

    // -------- HELPERS --------
    private function triggerBlock(): void
    {
        $this->triggerTripwire();
        $this->triggerTripwire();
        $this->triggerTripwire();
    }

    public function triggerAssertBlock(): void
    {
        $result = $this->triggerTripwire();
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    public function triggerAssertOke(): void
    {
        $result = $this->triggerTripwire();
        $this->assertEquals('next', $result);
    }

    private function triggerTripwire(): mixed
    {
        $request = request(); // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     * @param array<string> $data
     */
    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire_wires.$this->tripwire.reject_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(['tripwire.punish.score' => 21]);

        config(['tripwire.reset' => [
            'enabled' => true,
            'soft_delete' => false,
            'link_expiry_minutes' => 30,
        ]]);
    }
}
