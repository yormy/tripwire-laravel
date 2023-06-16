<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Sqli;

class SqliMsSqlExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Sqli::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/SqliMsSqlViolationsData.txt';

    protected array $accepting = [
        'saaaaaaa',
    ];

    protected array $violations;

    /**
     * @test
     * @group tripwire-log
     * @dataProvider accepting
     */
    public function should_accept(string $accept)
    {
        $this->assertAccept($accept);
    }

    /**
     * @test
     * @group tripwire-log
     * @dataProvider violations
     */
    public function should_block(string $violation)
    {
        $this->assertBlock($violation);
    }
}
