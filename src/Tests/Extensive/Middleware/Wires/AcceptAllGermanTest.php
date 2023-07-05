<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

class AcceptAllGermanTest extends BaseAcceptAll
{
    protected string $acceptsDataFile = './src/Tests/Dataproviders/AcceptsData-de_DE.txt';

    /**
     * @test
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
