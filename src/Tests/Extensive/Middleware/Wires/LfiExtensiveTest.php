<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Lfi;

class LfiExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Lfi::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/LfiViolationsData.txt';

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
