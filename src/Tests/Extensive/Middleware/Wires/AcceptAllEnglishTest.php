<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

class AcceptAllEnglishTest extends BaseAcceptAll
{
    protected static string $acceptsDataFile = './src/Tests/Dataproviders/AcceptsData-en_GB.txt';

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
