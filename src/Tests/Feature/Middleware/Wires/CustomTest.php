<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\BaseWire;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Custom;

class CustomTest extends BaseWireTester
{
    protected string $tripwire = 'custom';

    protected string $tripwireClass = Custom::class;

    protected static array $accepts = [
        "it!--That·I·won't,·then!--Bill's·to",
        'sss',
    ];

    protected static array $violations = [
        ' example.malicious',
    ];
}
