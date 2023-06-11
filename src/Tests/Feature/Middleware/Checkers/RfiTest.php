<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Rfi;

class RfiTest extends BaseMiddlewareTester
{
    protected string $tripwire ='rfi';

    protected $tripwireClass = Rfi::class;

    protected array $accepting = [
        'random',
    ];

    protected array $violations = [
        "https://example.com/danger.json",
    ];
}
