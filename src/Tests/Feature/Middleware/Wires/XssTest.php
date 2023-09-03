<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;

class XssTest extends BaseWireTester
{
    protected string $tripwire = 'xss';

    protected $tripwireClass = Xss::class;

    protected static array $accepts = [
        "it!--That·I·won't,·then!--Bill's·to",
        'sss',
        'net subscription'
    ];

    protected static array $violations = [
        '<script>',
        '#-moz-binding:#u',
        '<script>alert(123)</script>',
    ];
}
