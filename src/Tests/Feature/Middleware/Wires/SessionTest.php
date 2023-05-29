<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Session;

class SessionTest extends BaseWireTester
{
    protected string $tripwire = 'session';

    protected $tripwireClass = Session::class;

    protected array $accepts = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        ':a:9:{',
    ];
}
