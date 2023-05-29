<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;

class SqliTest extends BaseWireTester
{
    protected string $tripwire = 'sqli';

    protected $tripwireClass = Sqli::class;

    protected array $accepts = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        '-1+union+select+1,2,3,4,5,6,7,8,9,(SELECT+password+FROM+users+WHERE+ID=1',
        '(union select)',
    ];
}
