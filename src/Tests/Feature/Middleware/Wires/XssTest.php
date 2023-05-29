<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;

class XssTest extends BaseWireTester
{
    protected string $tripwire = 'xss';

    protected $tripwireClass = Xss::class;

    protected array $accepts = [
        "it!--That·I·won't,·then!--Bill's·to",
        'sss',
    ];

    protected array $violations = [
        '<script>',
        '#-moz-binding:#u',
        '<script>alert(123)</script>',
    ];
}
