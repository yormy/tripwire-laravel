<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;

class SqliPostgresExtensiveTest extends BaseExtensive
{
    protected string $tripwireClass = Sqli::class;

    protected static string $violationsDataFile = './src/Tests/Dataproviders/SqliPostgresViolationsData.txt';

    protected static array $accepts = [
        'saaaaaaa',
    ];

    protected static array $violations;

    /**
     * @test
     *
     * @group tripwire-sqli
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
     * @group tripwire-sqli
     *
     * @dataProvider violations
     */
    public function should_block(string $violation): void
    {
        $this->assertBlock($violation);
    }
}
