<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Geo;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Lfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Php;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Referer;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Rfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Session;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Swear;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class AcceptAllEnglishTest extends BaseAcceptAll
{
    protected string $acceptsDataFile = './src/Tests/Dataproviders/AcceptsData-en_GB.txt';

    /**
     * test
     *
     * @group tripwire-accept-all
     *
     * @dataProvider accepts
     */
    public function should_accept($accept): void
    {
        $this->setConfig();
        $this->triggerTripwire($accept);
    }
}
