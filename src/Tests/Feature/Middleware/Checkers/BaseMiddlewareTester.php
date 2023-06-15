<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BaseMiddlewareTester extends TestCase
{
    use TripwireTestTrait;


    /**
     * @test
     * @group tripwire-log
     * @dataProvider accepting
     */
    public function should_accept(string $accept)
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($accept);

        $this->assertNotLogged($startCount);

        $this->assertEquals('next', $result);
    }

    /**
     * @test
     * @group tripwire-log
     * @dataProvider violations
     */
    public function should_block(string $violation)
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($violation);

        $this->assertNotEquals('next', $result, 'Should block but did not');
        $this->assertEquals(409, $result->getStatusCode());

        $this->assertLogAddedToDatabase($startCount);
    }
}
