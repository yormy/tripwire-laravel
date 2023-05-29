<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;

class SqliExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Sqli::class;

    protected static string $violationsDataFile = './src/Tests/Dataproviders/SqliViolationsData.txt';

    protected static array $accepts = [
        'saaaaaaa',
    ];

    protected static array $violations;

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
