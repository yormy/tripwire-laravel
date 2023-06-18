<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;

class TextTest extends BaseMiddlewareTester
{
    protected string $tripwire ='text';

    protected $tripwireClass = Text::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations = [
       '\x00',
    ];
}
