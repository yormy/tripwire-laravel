<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\TriggerIgnores;

class InputIgnoreTest extends BaseTriggerIgnore
{
    /**
     * test
     * @group tripwire-ignore
     */
    public function Global_ignore_input_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(["tripwire.ignore.inputs" => ['foo']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     * @group tripwire-ignore
     */
    public function Global_ignore_url_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(["tripwire.urls.except" => ['http://localhost']]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }


}
