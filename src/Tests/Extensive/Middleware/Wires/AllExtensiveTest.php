<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class AllExtensiveTest extends TestCase
{
    protected $tripwireClass = Xss::class;
    public string $tripwire ='xss';

    protected string $violationsDataFile = './src/Tests/Dataproviders/XssViolationsData.txt';

    protected array $accepting = [
        'saaaaaaa',
    ];

    protected array $violations;

    /**
     * @test
     *
     * @group aaa
     *
     * dataProvider accepting
     */
    public function should_accept(): void
    {
        $this->setConfig();

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire('ff');

        $this->assertNotLogged($startCount);

        $this->assertEquals('next', $result);
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);
    }

    protected function triggerTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);

        $wire = new $this->tripwireClass($request);

        return $wire->handle($request, $this->getNextClosure());
    }

    protected function assertNotLogged($startCount): void
    {
        $this->assertEquals($startCount, TripwireLog::count());
    }



}
