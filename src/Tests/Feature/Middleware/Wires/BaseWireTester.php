<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\BaseWire;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\DataObjects\Tripwire;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BaseWireTester extends TestCase
{
    use TripwireTestTrait;

    protected string $tripwire;

    protected string $tripwireClass;

    /**
     * @test
     *
     * @group tripwire-log
     *
     * @dataProvider accepts
     */
    public function should_accept(string $accept): void
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($accept);

        $this->assertNotLogged($startCount);

        $this->assertEquals('next', $result);
    }

    /**
     * @test
     *
     * @group tripwire-log
     *
     * @dataProvider violations
     */
    public function should_block(string $violation): void
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($violation);

        $this->assertNotEquals('next', $result, 'Should block but did not');
        $this->assertEquals(Tripwire::TRIPWIRE_CODE_WIRE, $result->getStatusCode());

        $this->assertLogAddedToDatabase($startCount);
    }

    /**
     * @group tripwire-log
     * @group tripwire
     */
    public function Use_default_should_block(): void
    {
        $this->setConfigDefault();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($this->violations[0]);

        $this->assertNotEquals('next', $result, 'Should block but did not');
        $this->assertEquals(Tripwire::TRIPWIRE_CODE_DEFAULT, $result->getStatusCode());

        $this->assertLogAddedToDatabase($startCount);
    }
}
