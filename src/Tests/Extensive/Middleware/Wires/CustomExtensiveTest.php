<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Custom;

class CustomExtensiveTest extends BaseExtensive
{
    protected string $tripwireClass = Custom::class;

    protected static string $violationsDataFile = './src/Tests/Dataproviders/CustomViolationsData.txt';

    protected static string $acceptsDataFile = './src/Tests/Dataproviders/CustomAcceptsData.txt';

    /**
     * @test
     *
     * @group aaa
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
     * @group aaa
     *
     * @dataProvider violations
     */
    public function should_block(string $violation): void
    {
        $this->assertBlock($violation);
    }
}
