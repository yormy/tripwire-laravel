<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;

class XssExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Xss::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/XssViolationsData.txt';

    protected array $accepts = [
        'saaaaaaa',
    ];

    protected array $violations;

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
