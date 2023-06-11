<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class TextTest extends TestCase
{
    use TripwireTestTrait;

    protected string $tripwire ='text';

    protected $tripwireClass = Text::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        'aaa',
       '\x00',
    ];

    /**
     * @test
     * @dataProvider accepting
     */
    public function expects_accept(string $accept)
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($accept);

        $this->assertNotLogged($startCount);

        $this->assertEquals('next', $result);
    }

    /**
     * test
     * @dataProvider violations
     */
    public function expects_tripwire(string $violation)
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire($violation);

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals(409, $result->getStatusCode());
    }

}
