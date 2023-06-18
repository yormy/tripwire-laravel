<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;

class SqliMySqlExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Sqli::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/SqliMySqlViolationsData.txt';

    protected array $accepting = [
        'saaaaaaa',
    ];

    protected array $violations;

    /**
     * @test
     *
     * @group tripwire-log
     *
     * @dataProvider accepting
     */
    public function should_accept(string $accept)
    {
        $this->assertAccept($accept);
    }

    /**
     * @test
     *
     * @group tripwire-log
     *
     * @dataProvider violations
     */
    public function should_block(string $violation)
    {
        $this->assertBlock($violation);
    }
}
