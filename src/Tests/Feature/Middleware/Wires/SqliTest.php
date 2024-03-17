<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;

class SqliTest extends BaseWireTester
{
    protected string $tripwire = 'sqli';

    protected string $tripwireClass = Sqli::class;

    protected static array $accepts = [
        'dsfsdf',
        'sss',
    ];

    protected static array $violations = [
        '-1+union+select+1,2,3,4,5,6,7,8,9,(SELECT+password+FROM+users+WHERE+ID=1',
        '(union select)',
    ];
}
