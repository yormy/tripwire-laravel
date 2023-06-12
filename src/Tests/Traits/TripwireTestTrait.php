<?php

namespace Yormy\TripwireLaravel\Tests\Traits;

use Yormy\TripwireLaravel\Models\TripwireLog;

trait TripwireTestTrait
{
    public function accepting()
    {
        $providerArray = [];
        foreach ($this->accepting as $accept)
        {
            $providerArray[$accept] = [$accept];
        }
        return $providerArray;
    }

    public function violations()
    {
        $providerArray = [];
        foreach ($this->violations as $violation)
        {
            $providerArray[$violation] = [$violation];
        }
        return $providerArray;
    }

    protected function setConfig()
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

        $checker = new $this->tripwireClass($request);

        return $checker->handle($request, $this->getNextClosure());
    }

    protected function triggerJsonTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);
        $request->headers->set('Accept', 'application/json');

        $checker = new $this->tripwireClass($request);

        return $checker->handle($request, $this->getNextClosure());
    }

    protected function assertLogAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }

    protected function assertNotLogged($startCount)
    {
        $this->assertEquals($startCount, TripwireLog::count());
    }

    protected function assertFirewallTripped($result, int $expectedCode = 409)
    {
        $this->assertEquals($result->getStatusCode(), $expectedCode);
    }
}
