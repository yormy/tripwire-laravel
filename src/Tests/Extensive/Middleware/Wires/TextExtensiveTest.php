<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;

class TextExtensiveTest extends BaseExtensive
{
    protected $tripwireClass = Text::class;

    protected string $violationsDataFile = './src/Tests/Dataproviders/TextViolationsData.txt';

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
