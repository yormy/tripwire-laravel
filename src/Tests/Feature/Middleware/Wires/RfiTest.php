<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Rfi;

class RfiTest extends BaseWireTester
{
    protected string $tripwire = 'rfi';

    protected $tripwireClass = Rfi::class;

    protected static array $accepts = [
        'random',
    ];

    protected static array $violations = [
        'https://example.com/danger.json',
    ];
}
