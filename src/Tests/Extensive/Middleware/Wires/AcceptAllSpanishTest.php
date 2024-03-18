<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

class AcceptAllSpanishTest extends BaseAcceptAll
{
    protected static string $acceptsDataFile = './src/Tests/Dataproviders/AcceptsData-es_ES.txt';

    /**
     * @test
     *
     * @group tripwire-accept-all
     *
     * @dataProvider accepts
     */
    public function should_accept(string $accept): void
    {
        $this->setConfig();
        $this->triggerTripwire($accept);
    }
}
