<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class XssExtensiveTest extends TestCase
{
    use TripwireTestTrait;

    protected string $tripwire ='xss';

    protected $tripwireClass = Xss::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->violations = file('./src/Tests/Dataproviders/XssViolationsData.txt');

        // ignore commented out with #
        foreach ($this->violations as $index => $violation) {
            if(str_starts_with($violation, '##')) {
                unset ($this->violations[$index]);
            }
        }

        parent::__construct($name, $data, $dataName);


    }

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
