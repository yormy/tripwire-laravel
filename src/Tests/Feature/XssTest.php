<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;

class XssTest extends BaseMiddlewareTester
{
    protected string $tripwire ='xss';

    protected $tripwireClass = Xss::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        "<script>",
        '#-moz-binding:#u',
        '<script>alert(123)</script>'
    ];
}
