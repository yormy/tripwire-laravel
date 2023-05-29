<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Custom;

class CustomTest extends BaseWireTester
{
    protected string $tripwire = 'custom';

    protected $tripwireClass = Custom::class;

    protected array $accepts = [
        "it!--That·I·won't,·then!--Bill's·to",
        'sss',
    ];

    protected array $violations = [
        ' example.malicious',
    ];
}
