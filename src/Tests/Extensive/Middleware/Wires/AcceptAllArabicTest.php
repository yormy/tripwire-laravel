<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

class AcceptAllArabicTest extends BaseAcceptAll
{
    protected string $acceptsDataFile = './src/Tests/Dataproviders/AcceptsData-ar_SA.txt';

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
