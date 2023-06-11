<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Rfi;

class RfiTest extends BaseMiddlewareTester
{
    protected string $tripwire ='rfi';

    protected $tripwireClass = Rfi::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        "https://example.com/danger.json",
    ];
}
