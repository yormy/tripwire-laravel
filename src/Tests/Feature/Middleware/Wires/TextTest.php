<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;

class TextTest extends BaseWireTester
{
    protected string $tripwire = 'text';

    protected $tripwireClass = Text::class;

    protected array $accepts = [
        'hello',
    ];

    protected array $violations = [
        '\x00',
    ];
}
