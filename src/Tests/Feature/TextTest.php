<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

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
        'aaa',
       '\x00',
    ];
}
