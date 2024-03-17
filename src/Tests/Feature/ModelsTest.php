<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\DataObjects\Config\MissingModelConfig;
use Yormy\TripwireLaravel\Http\Middleware\Wires\BaseWire;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\ExceptionInspector;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class ModelsTest extends TestCase
{
    use TripwireTestTrait;

    const HTTP_TRIPWIRE_CODE = 409;

    protected string $tripwire;

    protected BaseWire $tripwireClass;

    /**
     * @test
     *
     * @group tripwire-models
     */
    public function Models_missing_disabled_Trigger_Skip(): void
    {
        config(['tripwire_wires.model404.enabled' => false]);

        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerModelNotFound();

        $this->assertNotLogged($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-models
     */
    public function Models_missing_log(): void
    {
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerModelNotFound();

        $this->assertLogAddedToDatabase($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-models
     */
    public function Model_missing_Added_block(): void
    {
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerModelNotFound();
        $this->triggerModelNotFound();
        $this->triggerModelNotFound();

        $endCount = TripwireLog::count();

        $this->assertGreaterThan($startCount, $endCount);
    }

    private function triggerModelNotFound(): void
    {
        try {
            TripwireLog::findOrFail(999999);
        } catch (\Throwable $e) {
            (new ExceptionInspector())->inspect($e);
        }
    }

    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);

        $config = MissingModelConfig::make()->only([Tripwirelog::class])->except([]);
        config(['tripwire_wires.model404.tripwires' => [$config]]);
    }
}
