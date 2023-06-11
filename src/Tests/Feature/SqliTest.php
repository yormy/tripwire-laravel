<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Sqli;

class SqliTest extends BaseMiddlewareTester
{
    protected string $tripwire ='sqli';

    protected $tripwireClass = Sqli::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
        "-1+union+select+1,2,3,4,5,6,7,8,9,(SELECT+password+FROM+users+WHERE+ID=1",
    ];
}
