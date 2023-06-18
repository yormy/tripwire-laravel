<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\TriggerIgnores;

class InputIgnoreTest extends BaseTriggerIgnore
{
    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Global_ignore_input_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire.ignore.inputs' => ['foo']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Global_ignore_url_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire.urls.except' => ['http://localhost/path/to/location']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Feature_ignore_url_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire_wires.text.urls.except' => ['http://localhost/path/to/location']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Global_not_included_url_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire.urls.only' => ['http://xxxxx']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Feature_not_included_url_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire_wires.text.urls.only' => ['http://xxxxx']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Global_exclude_path_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire.urls.except' => ['path/to/*']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Feature_exclude_path_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire_wires.text.urls.except' => ['path/to/*']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }
}
