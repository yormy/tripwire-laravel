<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Tests\TestCase;

class DefaultFilterTest extends TestCase
{
    private string $tripwire = 'text';

    const HTTP_TRIPWIRE_CODE = 409;

    const TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     *
     * @group tripwire-filter
     */
    public function tigger_Default_disabled_Continue()
    {
        $this->setDefaultConfig();
        $this->triggerAssertBlock();

        config(['tripwire.enabled' => false]);
        $this->triggerAssertOke();
    }

    /**
     * @test
     *
     * @group tripwire-filter
     */
    public function tigger_Default_ignore_ip_Continue()
    {
        $this->setDefaultConfig();
        $this->triggerAssertBlock();

        $currentIp = request()->ip();
        config(['tripwire.whitelist.ips' => [$currentIp]]);
        $this->triggerAssertOke();
    }

    /**
     * @test
     *
     * @group tripwire-filter
     */
    public function tigger_Default_ignore_input_Continue()
    {
        $this->setDefaultConfig();
        $this->triggerAssertBlock();

        config(['tripwire.ignore.inputs' => ['foo']]);
        $this->triggerAssertOke();
    }

    /**
     * @test
     *
     * @group tripwire-filter
     */
    public function tigger_Default_ignore_url_only_Continue()
    {
        $this->setDefaultConfig();
        $this->triggerAssertBlock();

        config(['tripwire.urls.only' => ['foo']]);
        $this->triggerAssertOke();
    }

    /**
     * @test
     *
     * @group tripwire-filter
     */
    public function tigger_Default_ignore_url_except_Continue()
    {
        $this->setDefaultConfig();
        $this->triggerAssertBlock();

        $url = request()->url();
        config(['tripwire.urls.except' => [$url]]);
        $this->triggerAssertOke();
    }

    public function triggerAssertBlock()
    {
        $result = $this->triggerTripwire();
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    public function triggerAssertOke()
    {
        $result = $this->triggerTripwire();
        $this->assertEquals('next', $result);
    }

    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data = [])
    {
        config(['tripwire.trigger_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
    }
}
