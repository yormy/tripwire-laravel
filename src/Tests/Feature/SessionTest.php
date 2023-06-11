<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Session;

class SessionTest extends BaseMiddlewareTester
{
    protected string $tripwire ='session';

    protected $tripwireClass = Session::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        ":a:9:{",
    ];
}
