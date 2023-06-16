<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;

class XssExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Xss::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/XssViolationsData.txt';

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
