<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;

class XssTest extends BaseWireTester
{
    protected string $tripwire = 'xss';

    protected string $tripwireClass = Xss::class;

    protected static array $accepts = [
        "it!--That·I·won't,·then!--Bill's·to",
        'sss',
        'hello <bold> something',  // html never allowed
        'net subscription',
        'N2IyZjliMGI1NTY4OGJkYTMwM2VjYWY', // regression test
    ];

    protected static array $violations = [
        '<script>',
        '&lt;!--[if gte IE 4]&gt;',
        '#-moz-binding:#u',
        '<script>alert(123)</script>',
    ];
}
