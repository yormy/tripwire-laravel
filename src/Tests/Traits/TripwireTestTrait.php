<?php

namespace Yormy\TripwireLaravel\Tests\Traits;

use Yormy\TripwireLaravel\Models\TripwireLog;

trait TripwireTestTrait
{
    protected function setConfig()
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);
    }

    protected function triggerTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);

        $checker = new $this->tripwireClass($request);

        return $checker->handle($request, $this->getNextClosure());
    }

    protected function assertLogAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }

    protected function assertFirewallTripped($result)
    {
        $this->assertEquals($result->getStatusCode(), 409);
    }
}
