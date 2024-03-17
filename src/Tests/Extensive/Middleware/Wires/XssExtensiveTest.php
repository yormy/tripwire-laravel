<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;

class XssExtensiveTest extends BaseExtensive
{
    protected string $tripwireClass = Xss::class;

    protected static string $violationsDataFile = './src/Tests/Dataproviders/XssViolationsData.txt';

    protected static array $accepts = [
        'saaaaaaa',
    ];

    protected static array $violations;

    /**
     * @test
     *
     * @group tripwire-log
     *
     * @dataProvider accepts
     */
    public function should_accept(string $accept): void
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
    public function should_block(string $violation): void
    {
        $this->assertBlock($violation);
    }
}
