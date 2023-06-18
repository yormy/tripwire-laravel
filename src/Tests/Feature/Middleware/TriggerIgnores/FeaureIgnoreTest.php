<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\TriggerIgnores;

class FeaureIgnoreTest extends BaseTriggerIgnore
{
    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Package_disabled_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire.enabled' => false]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @group tripwire-ignore
     */
    public function Feature_disabled_Trigger_No_exception()
    {
        $this->setDefaultConfig();
        config(['tripwire_wires.text.enabled' => false]);

        $this->triggerTripwire();

        $this->assertTrue(true);
    }
}
