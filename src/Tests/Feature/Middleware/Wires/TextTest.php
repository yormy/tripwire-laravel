<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;

class TextTest extends BaseWireTester
{
    protected string $tripwire = 'text';

    protected $tripwireClass = Text::class;

    protected static array $accepts = [
        'hello',
    ];

    protected static array $violations = [
        '\x00',
    ];
}
