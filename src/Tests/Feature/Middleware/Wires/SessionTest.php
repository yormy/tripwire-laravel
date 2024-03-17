<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Session;

class SessionTest extends BaseWireTester
{
    protected string $tripwire = 'session';

    protected string $tripwireClass = Session::class;

    protected static array $accepts = [
        'dsfsdf',
        'sss',
    ];

    protected static array $violations = [
        ':a:9:{',
    ];
}
