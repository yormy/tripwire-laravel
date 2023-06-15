<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Checkers;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;

class XssTest extends BaseMiddlewareTester
{
    protected string $tripwire ='xss';

    protected $tripwireClass = Xss::class;

    protected array $accepting = [
        'dsfsdf',
        'sss',
    ];

    protected array $violations;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->violations = file('./src/Tests/Dataproviders/XssViolationsData.txt');

        parent::__construct($name, $data, $dataName);


    }
}
