<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;

class SqliMySqlExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Sqli::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/SqliMySqlViolationsData.txt';

    protected array $accepts = [
        'saaaaaaa',
    ];

    protected array $violations;

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
