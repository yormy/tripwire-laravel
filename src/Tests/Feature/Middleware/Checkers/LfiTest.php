<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Lfi;

class LfiTest extends BaseMiddlewareTester
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
