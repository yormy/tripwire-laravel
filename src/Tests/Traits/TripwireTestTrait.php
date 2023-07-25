<?php

namespace Yormy\TripwireLaravel\Tests\Traits;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\DataObjects\Tripwire;

trait TripwireTestTrait
{
    /**
     * @return array[]
     *
     * @psalm-return array<list{mixed}>
     */
    public static function accepts(): array
    {
        if (isset(static::$acceptsDataFile)) {
            static::$accepts = static::loadFile(static::$acceptsDataFile);
        }

        $providerArray = [];
        foreach (static::$accepts as $accept) {
            $providerArray[$accept] = [$accept];
        }

        return $providerArray;
    }

    /**
     * @return array[]
     *
     * @psalm-return array<list{mixed}>
     */
    public static function violations(): array
    {
        if (isset(static::$violationsDataFile)) {
            static::$violations = static::loadFile(static::$violationsDataFile);
        }

        $providerArray = [];
        foreach (static::$violations as $violation) {
            $providerArray[$violation] = [$violation];
        }

        return $providerArray;
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.reject_response.html" => $settings]);
    }

    protected function setConfigDefault(): void
    {
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);

        $settings = ['code' => Tripwire::TRIPWIRE_CODE_DEFAULT];
        config(['tripwire.reject_response.html' => $settings]);
        config(['tripwire_wires.'.$this->tripwire.'.reject_response.html' => []]);
    }

    protected function triggerTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);

        $wire = new $this->tripwireClass($request);

        return $wire->handle($request, $this->getNextClosure());
    }

    protected function triggerJsonTripwire(string $input)
    {
        $request = $this->app->request;
        $request->query->set('foo', $input);
        $request->headers->set('Accept', 'application/json');

        $wire = new $this->tripwireClass($request);

        return $wire->handle($request, $this->getNextClosure());
    }

    protected function assertLogAddedToDatabase($startCount): void
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }

    protected function assertNotLogged($startCount): void
    {
        $this->assertEquals($startCount, TripwireLog::count());
    }

    protected function assertFirewallTripped($result, int $expectedCode = 409): void
    {
        $this->assertEquals($result->getStatusCode(), $expectedCode);
    }
}
