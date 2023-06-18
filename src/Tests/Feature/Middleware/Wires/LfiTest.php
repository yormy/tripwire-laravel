<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Lfi;

class LfiTest extends BaseWireTester
{
    protected string $tripwire ='lfi';

    protected $tripwireClass = Lfi::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        "../../etc/passwd",
    ];
}
