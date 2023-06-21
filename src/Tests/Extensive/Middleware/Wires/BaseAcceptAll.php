<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Custom;
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

class BaseAcceptAll extends TestCase
{
    protected string $violationsDataFile;
    protected string $acceptsDataFile;
    protected array $accepts = [];

    protected array $violations = [];

    protected array $tripwires = [
        Agent::class,
        Bot::class,
        // Geo::class,
        Lfi::class,
        Php::class,
        Referer::class,
        Rfi::class,
        Session::class,
        Sqli::class,
        Swear::class,
        Text::class,
        Xss::class,
        Custom::class
    ];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        if (isset($this->acceptsDataFile)) {
            $this->accepts = file($this->acceptsDataFile);
        }

        if (isset($this->violationsDataFile)) {
            $this->violations = file($this->violationsDataFile);
        }

        parent::__construct($name, $data, $dataName);
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];

        foreach ($this->tripwires as $wire) {
            $name = $wire::NAME;
            config(["tripwire_wires.$name.enabled" => true]);
            config(["tripwire_wires.$name.methods" => ['*']]);
            config(["tripwire_wires.$name.trigger_response.html" => $settings]);
        }
    }

    protected function triggerTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);

        foreach ($this->tripwires as $wire) {
            $wire = new $wire($request);
            $result = $wire->handle($request, $this->getNextClosure());
            $this->assertEquals('next', $result, strtoupper($wire::NAME .' tripped'));
        }
    }

    protected function assertNotLogged($startCount): void
    {
        $this->assertEquals($startCount, TripwireLog::count());
    }

    public function accepts(): array
    {
        $providerArray = [];
        foreach ($this->accepts as $accept) {
            $providerArray[$accept] = [$accept];
        }

        return $providerArray;
    }

}
